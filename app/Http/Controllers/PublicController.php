<?php

namespace App\Http\Controllers;

use App\Models\AppSettings;
use App\Models\Feedback;
use App\Models\FeedbackCategory;
use App\Models\FeedbackSettings;
use App\Models\FeedbackVote;
use App\Models\PublicUser;
use App\Models\Roadmap;
use App\Models\RoadmapItem;
use App\Models\Changelog;
use App\Models\Category;
use App\Models\Subscriber;
use App\Models\VoteOtp;
use App\Models\Testimonial;
use App\Models\KnowledgeBoard;
use App\Models\BoardArticle;
use App\Models\BoardCategory;
use App\Notifications\SubscriberVerificationNotification;
use App\Notifications\FeedbackSubmissionNotification;
use App\Notifications\VoteOtpNotification;
use App\Models\FeedbackComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PublicController extends Controller
{
    /**
     * Display public feedback board
     */
    public function home($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get feedback settings for this team
        $feedbackSettings = FeedbackSettings::forTeam($settings->team_id);

        // Check if user is logged in
        $isLoggedIn = Session::has('public_user_id');
        $publicUser = $isLoggedIn ? PublicUser::find(Session::get('public_user_id')) : null;

        // Get active feedback categories for this team
        $categories = FeedbackCategory::where('team_id', $settings->team_id)
            ->where('is_active', true)
            ->withCount(['feedbacks' => function ($query) use ($settings) {
                $query->where('team_id', $settings->team_id)
                    ->where('is_public', true)
                    ->where('is_verified', true);
            }])
            ->orderBy('name')
            ->get();

        // Get active roadmap statuses for this team
        $roadmaps = Roadmap::where('team_id', $settings->team_id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get public feedbacks grouped by category (only verified ones)
        $feedbacks = Feedback::with(['category', 'roadmap', 'votes'])
            ->where('team_id', $settings->team_id)
            ->where('is_public', true)
            ->where('is_verified', true)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('feedback_category_id');

        return view('public.home', compact(
            'settings',
            'categories',
            'roadmaps',
            'feedbacks',
            'feedbackSettings',
            'isLoggedIn',
            'publicUser'
        ));
    }

    /**
     * Display single feedback detail
     */
    public function showFeedback($uniqueUrl, $feedbackId)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get feedback settings for this team
        $feedbackSettings = FeedbackSettings::forTeam($settings->team_id);

        // Check if user is logged in
        $isLoggedIn = Session::has('public_user_id');
        $publicUser = $isLoggedIn ? PublicUser::find(Session::get('public_user_id')) : null;

        // Get the specific feedback
        $feedback = Feedback::with(['category', 'roadmap', 'publicComments.user', 'votes'])
            ->where('team_id', $settings->team_id)
            ->where('is_public', true)
            ->where('is_verified', true)
            ->where('id', $feedbackId)
            ->firstOrFail();

        // Get vote count and check if user has voted
        $voteCount = $feedback->votes()->count();
        $hasVoted = false;
        if ($isLoggedIn && $publicUser) {
            $hasVoted = $feedback->hasVotedBy($publicUser->id);
        } elseif (!$feedbackSettings->enable_login_for_voting && $feedbackSettings->enable_anonymous_voting) {
            $hasVoted = $feedback->hasVotedBy(null, null, request()->ip());
        }

        // Get related feedbacks (same category, excluding current)
        $relatedFeedbacks = Feedback::with(['category', 'roadmap', 'votes'])
            ->where('team_id', $settings->team_id)
            ->where('is_public', true)
            ->where('is_verified', true)
            ->where('id', '!=', $feedbackId)
            ->when($feedback->feedback_category_id, function ($query) use ($feedback) {
                return $query->where('feedback_category_id', $feedback->feedback_category_id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('public.feedback-detail', compact(
            'settings',
            'feedback',
            'feedbackSettings',
            'isLoggedIn',
            'publicUser',
            'voteCount',
            'hasVoted',
            'relatedFeedbacks'
        ));
    }

    /**
     * Display public roadmap
     */
    public function roadmap($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get active roadmap statuses for this team (only roadmap workflow)
        $roadmaps = Roadmap::where('team_id', $settings->team_id)
            ->where('workflow_type', 'roadmap workflow')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Get roadmap items grouped by roadmap status
        $roadmapItems = RoadmapItem::with(['feedback.category'])
            ->where('team_id', $settings->team_id)
            ->whereIn('roadmap_status_id', $roadmaps->pluck('id'))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('roadmap_status_id');

        return view('public.roadmap', compact('settings', 'roadmaps', 'roadmapItems'));
    }

    /**
     * Display public changelog
     */
    public function changelog($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get active categories with changelog counts
        $categories = \App\Models\Category::where('team_id', $settings->team_id)
            ->where('status', 'active')
            ->withCount(['changelogs' => function ($query) use ($settings) {
                $query->where('team_id', $settings->team_id)
                    ->where('status', 'Published');
            }])
            ->orderBy('name')
            ->get();

        // Get published changelogs for this team
        $changelogs = Changelog::with('category')
            ->where('team_id', $settings->team_id)
            ->where('status', 'Published')
            ->orderBy('published_date', 'desc')
            ->paginate(10);

        return view('public.changelog', compact('settings', 'changelogs', 'categories'));
    }

    /**
     * Display single changelog
     */
    public function showChangelog($uniqueUrl, $changelogId)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get the specific changelog
        $changelog = Changelog::with('category')
            ->where('team_id', $settings->team_id)
            ->where('status', 'Published')
            ->where('id', $changelogId)
            ->firstOrFail();

        // Get all published changelogs for filtering
        $allChangelogs = Changelog::with('category')
            ->where('team_id', $settings->team_id)
            ->where('status', 'Published')
            ->orderBy('published_date', 'desc')
            ->get();

        // Get categories with changelog count
        $categories = Category::where('status', 'active')
            ->withCount(['changelogs' => function($query) use ($settings) {
                $query->where('team_id', $settings->team_id)
                      ->where('status', 'Published');
            }])
            ->having('changelogs_count', '>', 0)
            ->get();

        return view('public.changelog-detail', compact('settings', 'changelog', 'allChangelogs', 'categories'));
    }

    /**
     * Display public testimonials
     */
    public function testimonials($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get published testimonials for this team
        $testimonials = Testimonial::where('team_id', $settings->team_id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.testimonials', compact('settings', 'testimonials'));
    }

    /**
     * Display single testimonial
     */
    public function showTestimonial($uniqueUrl, $testimonialId)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get the specific testimonial
        $testimonial = Testimonial::where('team_id', $settings->team_id)
            ->where('status', 'published')
            ->where('id', $testimonialId)
            ->firstOrFail();

        // Get other published testimonials for sidebar
        $otherTestimonials = Testimonial::where('team_id', $settings->team_id)
            ->where('status', 'published')
            ->where('id', '!=', $testimonialId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('public.testimonial-detail', compact('settings', 'testimonial', 'otherTestimonials'));
    }

    /**
     * Display public knowledge board
     */
    public function knowledge($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get published public knowledge boards for this team
        $knowledgeBoards = KnowledgeBoard::where('team_id', $settings->team_id)
            ->where('visibility_type', 'public')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('public.knowledge', compact('settings', 'knowledgeBoards'));
    }

    /**
     * Display single knowledge board with theme-based layout
     */
    public function showKnowledge($uniqueUrl, $knowledgeBoardId)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get the specific knowledge board
        $knowledgeBoard = KnowledgeBoard::where('team_id', $settings->team_id)
            ->where('visibility_type', 'public')
            ->where('status', 'published')
            ->where('id', $knowledgeBoardId)
            ->firstOrFail();

        // Get categories with their published articles
        $categories = BoardCategory::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('status', 'active')
            ->whereNull('parent_category_id') // Get parent categories only
            ->with(['childCategories' => function($query) {
                $query->where('status', 'active')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Get all published articles for this board, grouped by category
        $articles = BoardArticle::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('status', 'published')
            ->with('boardCategory')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('board_category_id');

        // Determine view based on document_type
        $view = $knowledgeBoard->document_type === 'manual'
            ? 'public.knowledge-board.manual'
            : 'public.knowledge-board.help-document';

        return view($view, compact('settings', 'knowledgeBoard', 'categories', 'articles'));
    }

    /**
     * Display single knowledge board article
     */
    public function showKnowledgeArticle($uniqueUrl, $knowledgeBoardId, $articleId)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Get the knowledge board
        $knowledgeBoard = KnowledgeBoard::where('team_id', $settings->team_id)
            ->where('visibility_type', 'public')
            ->where('status', 'published')
            ->where('id', $knowledgeBoardId)
            ->firstOrFail();

        // Get the article
        $article = BoardArticle::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('status', 'published')
            ->where('id', $articleId)
            ->with(['boardCategory', 'author'])
            ->firstOrFail();

        // Get categories for sidebar navigation
        $categories = BoardCategory::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('status', 'active')
            ->whereNull('parent_category_id')
            ->with(['childCategories' => function($query) {
                $query->where('status', 'active')->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Get articles grouped by category for sidebar
        $allArticles = BoardArticle::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('board_category_id');

        // Get prev/next articles in the same category
        $categoryArticles = BoardArticle::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('board_category_id', $article->board_category_id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->get();

        $currentIndex = $categoryArticles->search(function($item) use ($article) {
            return $item->id === $article->id;
        });

        $prevArticle = $currentIndex > 0 ? $categoryArticles[$currentIndex - 1] : null;
        $nextArticle = $currentIndex < $categoryArticles->count() - 1 ? $categoryArticles[$currentIndex + 1] : null;

        // Determine view based on document_type
        $view = $knowledgeBoard->document_type === 'manual'
            ? 'public.knowledge-board.article-manual'
            : 'public.knowledge-board.article-help';

        return view($view, compact('settings', 'knowledgeBoard', 'article', 'categories', 'allArticles', 'prevArticle', 'nextArticle'));
    }

    /**
     * Search knowledge board articles (AJAX)
     */
    public function searchKnowledgeArticles($uniqueUrl, $knowledgeBoardId, Request $request)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->first();

        if (!$settings) {
            return response()->json(['results' => []]);
        }

        // Get the knowledge board
        $knowledgeBoard = KnowledgeBoard::where('team_id', $settings->team_id)
            ->where('visibility_type', 'public')
            ->where('status', 'published')
            ->where('id', $knowledgeBoardId)
            ->first();

        if (!$knowledgeBoard) {
            return response()->json(['results' => []]);
        }

        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }

        // Search articles by title and content
        $articles = BoardArticle::where('knowledge_board_id', $knowledgeBoard->id)
            ->where('status', 'published')
            ->where(function($q) use ($query) {
                $q->where('article_title', 'like', "%{$query}%")
                  ->orWhere('detailed_post', 'like', "%{$query}%");
            })
            ->with('boardCategory')
            ->limit(10)
            ->get()
            ->map(function($article) use ($uniqueUrl, $knowledgeBoard) {
                return [
                    'id' => $article->id,
                    'title' => $article->article_title,
                    'category' => $article->boardCategory->category_name ?? '',
                    'excerpt' => Str::limit(strip_tags($article->detailed_post), 100),
                    'url' => route('public.knowledge.article', [$uniqueUrl, $knowledgeBoard->id, $article->id])
                ];
            });

        return response()->json(['results' => $articles]);
    }

    /**
     * Display public subscribe page
     */
    public function subscribe($uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        return view('public.subscribe', compact('settings'));
    }

    /**
     * Handle subscription form submission
     */
    public function subscribeSubmit(Request $request, $uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        // Validate the request
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if email already exists
        $existingSubscriber = Subscriber::where('email', $request->email)->first();

        if ($existingSubscriber) {
            if ($existingSubscriber->status === 'subscribed') {
                return back()->with('error', 'This email is already subscribed.')->withInput();
            } elseif ($existingSubscriber->status === 'pending_verify') {
                return back()->with('error', 'This email is already registered. Please check your inbox for the verification email.')->withInput();
            }
        }

        // Create subscriber
        $subscriber = Subscriber::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'source' => 'online_subscribe',
            'status' => 'pending_verify',
            'subscribe_date' => now(),
        ]);

        // Generate token and send verification email
        $subscriber->generateVerificationToken();
        Notification::route('mail', $subscriber->email)
            ->notify(new SubscriberVerificationNotification($subscriber));

        return view('public.subscription-pending', compact('subscriber', 'settings'));
    }

    /**
     * Submit a new feedback idea (public)
     */
    public function submitFeedback(Request $request, $uniqueUrl)
    {
        // Find the app settings by unique URL
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();
        $feedbackSettings = FeedbackSettings::forTeam($settings->team_id);

        // Validate request
        $rules = [
            'idea' => 'required|string|max:500',
            'value_description' => 'nullable|string|max:2000',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'categories' => 'nullable|array|max:3',
            'categories.*' => 'exists:feedback_categories,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,webp,avif|mimetypes:image/jpeg,image/png,image/webp,image/avif|max:2048',
            'privacy_agree' => 'accepted',
        ];

        // Add hCaptcha validation if enabled
        if ($feedbackSettings->enable_captcha) {
            $rules['h-captcha-response'] = 'required';
        }

        $validator = Validator::make($request->all(), $rules, [
            'idea.required' => 'Please tell us your idea.',
            'full_name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'privacy_agree.accepted' => 'You must agree to the Privacy Policy and Terms.',
            'h-captcha-response.required' => 'Please complete the captcha verification.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Verify hCaptcha if enabled
        if ($feedbackSettings->enable_captcha) {
            $hcaptchaResponse = $request->input('h-captcha-response');
            $verified = $this->verifyHcaptcha($hcaptchaResponse);
            if (!$verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Captcha verification failed. Please try again.',
                ], 422);
            }
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('feedback-images', 'public');
        }

        // Find or create public user
        $publicUser = PublicUser::firstOrCreate(
            ['email' => $request->email],
            ['full_name' => $request->full_name]
        );

        // Update name if provided and user exists without name
        if (!$publicUser->full_name && $request->full_name) {
            $publicUser->full_name = $request->full_name;
            $publicUser->save();
        }

        // Get first category if provided
        $categoryId = null;
        $tagNames = [];
        if ($request->has('categories') && count($request->categories) > 0) {
            $categoryId = $request->categories[0];

            // Get category names for tags instead of IDs
            $selectedCategories = FeedbackCategory::whereIn('id', $request->categories)
                ->where('team_id', $settings->team_id)
                ->pluck('name')
                ->toArray();
            $tagNames = $selectedCategories;
        }

        // Get default roadmap status (first active feedback workflow status)
        $defaultRoadmap = Roadmap::where('team_id', $settings->team_id)
            ->where('workflow_type', 'feedback workflow')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->first();

        // Create the feedback (unverified, private by default until admin approves)
        $feedback = Feedback::create([
            'team_id' => $settings->team_id,
            'idea' => $request->idea,
            'value_description' => $request->value_description,
            'name' => $request->full_name,
            'email' => $request->email,
            'feedback_category_id' => $categoryId,
            'roadmap_id' => $defaultRoadmap ? $defaultRoadmap->id : null,
            'tags' => $tagNames,
            'image' => $imagePath,
            'public_user_id' => $publicUser->id,
            'source' => 'User Submitted',
            'is_public' => false,
            'is_verified' => false,
            'login_access_enabled' => false,
        ]);

        // Generate verification token
        $feedback->generateVerificationToken();

        // Return response immediately, then send email
        $response = response()->json([
            'success' => true,
            'message' => 'Your idea has been submitted! Please check your email to confirm your submission.',
        ]);

        // Use dispatch to send email after response
        $email = $request->email;
        dispatch(function () use ($feedback, $settings, $email) {
            Notification::route('mail', $email)
                ->notify(new FeedbackSubmissionNotification($feedback, $settings));
        })->afterResponse();

        return $response;
    }

    /**
     * Verify feedback submission via email link
     */
    public function verifyFeedback($token)
    {
        $feedback = Feedback::where('verification_token', $token)->first();

        if (!$feedback) {
            return view('public.feedback-verification-failed', [
                'message' => 'Invalid verification link.'
            ]);
        }

        if ($feedback->is_verified) {
            $settings = AppSettings::where('team_id', $feedback->team_id)->first();
            return view('public.feedback-verification-success', [
                'feedback' => $feedback,
                'settings' => $settings,
                'alreadyVerified' => true
            ]);
        }

        // Mark as verified
        $feedback->markAsVerified();

        // Mark public user as verified if not already
        if ($feedback->publicUser && !$feedback->publicUser->is_verified) {
            $feedback->publicUser->markEmailAsVerified();
        }

        $settings = AppSettings::where('team_id', $feedback->team_id)->first();

        return view('public.feedback-verification-success', [
            'feedback' => $feedback,
            'settings' => $settings,
            'alreadyVerified' => false
        ]);
    }

    /**
     * Vote for a feedback
     */
    public function vote(Request $request, $uniqueUrl, $feedbackId)
    {
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();
        $feedbackSettings = FeedbackSettings::forTeam($settings->team_id);

        $feedback = Feedback::where('id', $feedbackId)
            ->where('team_id', $settings->team_id)
            ->firstOrFail();

        $ip = $request->ip();
        $publicUserId = Session::get('public_user_id');
        $isLoggedIn = Session::has('public_user_id');
        $voterEmail = $request->input('email');

        // Check if login is required for voting
        if ($feedbackSettings->enable_login_for_voting && !$isLoggedIn) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to vote.',
                'require_login' => true
            ], 403);
        }

        // Check if anonymous voting is disabled
        if (!$feedbackSettings->enable_anonymous_voting && !$isLoggedIn) {
            return response()->json([
                'success' => false,
                'message' => 'Anonymous voting is not allowed. Please log in to vote.',
                'require_login' => true
            ], 403);
        }

        // Check rate limit
        if (FeedbackVote::hasExceededRateLimit($ip, $feedbackSettings->ip_rate_limit_per_hour)) {
            return response()->json([
                'success' => false,
                'message' => 'You have exceeded the voting limit. Please try again later.'
            ], 429);
        }

        // Check if already voted
        if (FeedbackVote::hasVoted($feedbackId, $publicUserId, $voterEmail, $isLoggedIn ? null : $ip)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted for this idea.'
            ], 400);
        }

        // Check if OTP verification is required for first-time voters
        if ($feedbackSettings->require_email_otp_first_vote && !$isLoggedIn) {
            // If no email provided, request email first
            if (!$voterEmail) {
                return response()->json([
                    'success' => false,
                    'require_otp' => true,
                    'message' => 'Email verification required for your first vote.'
                ]);
            }

            // Check if this email has been verified for this team before
            if (!VoteOtp::hasVerifiedEmail($voterEmail, $settings->team_id)) {
                return response()->json([
                    'success' => false,
                    'require_otp' => true,
                    'require_otp_verification' => true,
                    'message' => 'Please verify your email with the OTP code sent to you.'
                ]);
            }
        }

        // Create vote
        FeedbackVote::create([
            'feedback_id' => $feedbackId,
            'public_user_id' => $publicUserId,
            'voter_email' => $voterEmail,
            'voter_ip' => $ip,
            'device_fingerprint' => $request->header('X-Device-Fingerprint'),
            'voted_at' => now(),
        ]);

        $voteCount = $feedback->votes()->count();

        return response()->json([
            'success' => true,
            'message' => 'Vote recorded successfully!',
            'vote_count' => $voteCount
        ]);
    }

    /**
     * Request OTP for vote verification
     */
    public function requestVoteOtp(Request $request, $uniqueUrl, $feedbackId)
    {
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $feedback = Feedback::where('id', $feedbackId)
            ->where('team_id', $settings->team_id)
            ->firstOrFail();

        $email = $request->input('email');
        $ip = $request->ip();

        // Check if already verified for this team
        if (VoteOtp::hasVerifiedEmail($email, $settings->team_id)) {
            return response()->json([
                'success' => true,
                'already_verified' => true,
                'message' => 'Your email is already verified. You can vote now.'
            ]);
        }

        // Generate and send OTP
        $voteOtp = VoteOtp::generateFor($email, $feedbackId, $settings->team_id, $ip);

        // Send OTP email
        Notification::route('mail', $email)
            ->notify(new VoteOtpNotification($voteOtp, $settings));

        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your email. Please check your inbox.'
        ]);
    }

    /**
     * Verify OTP and cast vote
     */
    public function verifyVoteOtp(Request $request, $uniqueUrl, $feedbackId)
    {
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();
        $feedbackSettings = FeedbackSettings::forTeam($settings->team_id);

        $request->validate([
            'email' => 'required|email|max:255',
            'otp' => 'required|string|size:6',
        ]);

        $feedback = Feedback::where('id', $feedbackId)
            ->where('team_id', $settings->team_id)
            ->firstOrFail();

        $email = $request->input('email');
        $otp = $request->input('otp');
        $ip = $request->ip();

        // Verify OTP
        if (!VoteOtp::verify($email, $feedbackId, $otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification code. Please try again.'
            ], 400);
        }

        // Check if already voted
        if (FeedbackVote::hasVoted($feedbackId, null, $email, null)) {
            return response()->json([
                'success' => false,
                'message' => 'You have already voted for this idea.'
            ], 400);
        }

        // Create vote
        FeedbackVote::create([
            'feedback_id' => $feedbackId,
            'voter_email' => $email,
            'voter_ip' => $ip,
            'device_fingerprint' => $request->header('X-Device-Fingerprint'),
            'voted_at' => now(),
        ]);

        $voteCount = $feedback->votes()->count();

        return response()->json([
            'success' => true,
            'message' => 'Email verified and vote recorded successfully!',
            'vote_count' => $voteCount
        ]);
    }

    /**
     * Remove vote from a feedback
     */
    public function unvote(Request $request, $uniqueUrl, $feedbackId)
    {
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();

        $feedback = Feedback::where('id', $feedbackId)
            ->where('team_id', $settings->team_id)
            ->firstOrFail();

        $ip = $request->ip();
        $publicUserId = Session::get('public_user_id');
        $isLoggedIn = Session::has('public_user_id');

        // Remove vote
        $deleted = FeedbackVote::removeVote($feedbackId, $publicUserId, null, $isLoggedIn ? null : $ip);

        if ($deleted === 0) {
            return response()->json([
                'success' => false,
                'message' => 'No vote found to remove.'
            ], 400);
        }

        $voteCount = $feedback->votes()->count();

        return response()->json([
            'success' => true,
            'message' => 'Vote removed successfully!',
            'vote_count' => $voteCount
        ]);
    }

    /**
     * Store a public comment on a feedback
     */
    public function storePublicComment(Request $request, $uniqueUrl, $feedbackId)
    {
        $settings = AppSettings::where('unique_url', $uniqueUrl)->firstOrFail();
        $feedbackSettings = FeedbackSettings::forTeam($settings->team_id);

        $feedback = Feedback::where('id', $feedbackId)
            ->where('team_id', $settings->team_id)
            ->where('is_public', true)
            ->where('is_verified', true)
            ->firstOrFail();

        $isLoggedIn = Session::has('public_user_id');
        $publicUser = $isLoggedIn ? PublicUser::find(Session::get('public_user_id')) : null;

        // Check if login is required for commenting
        if ($feedbackSettings->enable_login_for_voting && !$isLoggedIn) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to comment.',
                'require_login' => true
            ], 403);
        }

        // Validate request
        $rules = [
            'comment' => 'required|string|max:2000',
        ];

        // If not logged in, require name and email
        if (!$isLoggedIn) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = 'required|email|max:255';
        }

        $validator = Validator::make($request->all(), $rules, [
            'comment.required' => 'Please enter a comment.',
            'name.required' => 'Please enter your name.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Create the comment
        $commentData = [
            'feedback_id' => $feedbackId,
            'comment' => $request->input('comment'),
            'is_internal' => false,
        ];

        if ($isLoggedIn && $publicUser) {
            $commentData['public_user_id'] = $publicUser->id;
            $commentData['commenter_name'] = $publicUser->full_name;
            $commentData['commenter_email'] = $publicUser->email;
        } else {
            $commentData['commenter_name'] = $request->input('name');
            $commentData['commenter_email'] = $request->input('email');
        }

        $comment = FeedbackComment::create($commentData);

        return response()->json([
            'success' => true,
            'message' => 'Comment posted successfully!',
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'commenter_name' => $comment->commenter_name,
                'created_at' => $comment->created_at->diffForHumans(),
            ]
        ]);
    }

    /**
     * Verify hCaptcha response
     */
    private function verifyHcaptcha($response)
    {
        $secret = config('services.hcaptcha.secret');

        if (!$secret) {
            return true; // Skip verification if secret not configured
        }

        $data = [
            'secret' => $secret,
            'response' => $response,
        ];

        $ch = curl_init('https://hcaptcha.com/siteverify');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);
        curl_close($ch);

        $resultJson = json_decode($result, true);

        return $resultJson['success'] ?? false;
    }
}
