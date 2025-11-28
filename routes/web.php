<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\AppConfigurationController;
use App\Http\Controllers\KnowledgeBoardController;
use App\Http\Controllers\BoardCategoryController;
use App\Http\Controllers\BoardArticleController;
use App\Http\Controllers\UserSegmentController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\RoadmapItemController;
use App\Http\Controllers\FeedbackCategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\TeamInvitationController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\PublicAuthController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\TestimonialTemplateController;
use App\Http\Controllers\SubdomainPublicController;
use Illuminate\Support\Facades\Route;

// Subdomain-based Public Routes (e.g., demo.wbapp.com:8000)
// These routes are checked first and only match when a valid subdomain is detected
Route::middleware(['web'])->group(function () {
    Route::get('/', function (\Illuminate\Http\Request $request) {
        // Check if this is a subdomain request
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->home($request);
        }
        // Default: redirect to login
        return redirect()->route('login');
    });

    Route::get('/roadmap', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->roadmap($request);
        }
        abort(404);
    })->name('subdomain.roadmap');

    Route::get('/changelog', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->changelog($request);
        }
        abort(404);
    })->name('subdomain.changelog');

    Route::get('/testimonials', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->testimonials($request);
        }
        abort(404);
    })->name('subdomain.testimonials');

    Route::get('/knowledge', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->knowledge($request);
        }
        abort(404);
    })->name('subdomain.knowledge');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public Subscriber Routes (No Authentication Required)
Route::post('/subscribe', [SubscriberController::class, 'subscribe'])->name('subscriber.subscribe');
Route::get('/verify/{token}', [SubscriberController::class, 'verify'])->name('subscriber.verify');
Route::get('/unsubscribe/{token}', [SubscriberController::class, 'unsubscribe'])->name('subscriber.unsubscribe');

// Public Authentication Routes (Magic Link)
Route::get('/auth/verify/{token}', [PublicAuthController::class, 'verifyMagicLink'])->name('public.auth.verify');

// Public Feedback Verification Route
Route::get('/feedback/verify/{token}', [PublicController::class, 'verifyFeedback'])->name('public.feedback.verify');

// Public Team Invitation Routes (No Authentication Required)
Route::get('/invitation/{token}', [TeamInvitationController::class, 'accept'])->name('team.invitation.accept');
Route::post('/invitation/{token}', [TeamInvitationController::class, 'acceptSignup'])->name('team.invitation.accept.signup');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Team Management
    Route::get('/team', [TeamInvitationController::class, 'index'])->name('team.manage');
    Route::post('/team/invite', [TeamInvitationController::class, 'invite'])->name('team.invitation.invite');
    Route::delete('/team/invitation/{invitation}', [TeamInvitationController::class, 'cancel'])->name('team.invitation.cancel');
    Route::delete('/team/member/{user}', [TeamInvitationController::class, 'removeMember'])->name('team.member.remove');
    Route::put('/team/member/{user}/role', [TeamInvitationController::class, 'updateRole'])->name('team.member.update-role');

    // Category Management
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.manage');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Changelog Management
    Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog.index');
    Route::get('/changelog/create', [ChangelogController::class, 'create'])->name('changelog.create');
    Route::post('/changelog', [ChangelogController::class, 'store'])->name('changelog.store');
    Route::get('/changelog/{changelog}', [ChangelogController::class, 'show'])->name('changelog.show');
    Route::get('/changelog/{changelog}/edit', [ChangelogController::class, 'edit'])->name('changelog.edit');
    Route::put('/changelog/{changelog}', [ChangelogController::class, 'update'])->name('changelog.update');
    Route::delete('/changelog/{changelog}', [ChangelogController::class, 'destroy'])->name('changelog.destroy');

    // Knowledge Board Management
    Route::get('/knowledge-board', [KnowledgeBoardController::class, 'index'])->name('knowledge-board.index');
    Route::get('/knowledge-board/create', [KnowledgeBoardController::class, 'create'])->name('knowledge-board.create');
    Route::post('/knowledge-board', [KnowledgeBoardController::class, 'store'])->name('knowledge-board.store');
    Route::get('/knowledge-board/{knowledgeBoard}', [KnowledgeBoardController::class, 'show'])->name('knowledge-board.show');

    // Board Category Management
    Route::get('/knowledge-board/{knowledgeBoard}/category/create', [BoardCategoryController::class, 'create'])->name('board-category.create');
    Route::post('/knowledge-board/{knowledgeBoard}/category', [BoardCategoryController::class, 'store'])->name('board-category.store');
    Route::get('/knowledge-board/{knowledgeBoard}/category/{category}', [BoardCategoryController::class, 'show'])->name('board-category.show');
    Route::get('/knowledge-board/{knowledgeBoard}/category/{category}/edit', [BoardCategoryController::class, 'edit'])->name('board-category.edit');
    Route::put('/knowledge-board/{knowledgeBoard}/category/{category}', [BoardCategoryController::class, 'update'])->name('board-category.update');
    Route::delete('/knowledge-board/{knowledgeBoard}/category/{category}', [BoardCategoryController::class, 'destroy'])->name('board-category.destroy');

    // Board Article Management
    Route::get('/knowledge-board/{knowledgeBoard}/article/create', [BoardArticleController::class, 'create'])->name('board-article.create');
    Route::post('/knowledge-board/{knowledgeBoard}/article', [BoardArticleController::class, 'store'])->name('board-article.store');
    Route::get('/knowledge-board/{knowledgeBoard}/article/{article}', [BoardArticleController::class, 'show'])->name('board-article.show');
    Route::get('/knowledge-board/{knowledgeBoard}/article/{article}/edit', [BoardArticleController::class, 'edit'])->name('board-article.edit');
    Route::put('/knowledge-board/{knowledgeBoard}/article/{article}', [BoardArticleController::class, 'update'])->name('board-article.update');
    Route::delete('/knowledge-board/{knowledgeBoard}/article/{article}', [BoardArticleController::class, 'destroy'])->name('board-article.destroy');

    // User Segment Management
    Route::get('/user-segment', [UserSegmentController::class, 'index'])->name('user-segment.index');
    Route::get('/user-segment/create', [UserSegmentController::class, 'create'])->name('user-segment.create');
    Route::post('/user-segment', [UserSegmentController::class, 'store'])->name('user-segment.store');
    Route::get('/user-segment/{userSegment}', [UserSegmentController::class, 'show'])->name('user-segment.show');
    Route::get('/user-segment/{userSegment}/edit', [UserSegmentController::class, 'edit'])->name('user-segment.edit');
    Route::put('/user-segment/{userSegment}', [UserSegmentController::class, 'update'])->name('user-segment.update');
    Route::delete('/user-segment/{userSegment}', [UserSegmentController::class, 'destroy'])->name('user-segment.destroy');

    // Persona Management
    Route::get('/personas', [PersonaController::class, 'index'])->name('personas.index');
    Route::get('/personas/create', [PersonaController::class, 'create'])->name('personas.create');
    Route::post('/personas', [PersonaController::class, 'store'])->name('personas.store');
    Route::get('/personas/{persona}', [PersonaController::class, 'show'])->name('personas.show');
    Route::get('/personas/{persona}/edit', [PersonaController::class, 'edit'])->name('personas.edit');
    Route::put('/personas/{persona}', [PersonaController::class, 'update'])->name('personas.update');
    Route::delete('/personas/{persona}', [PersonaController::class, 'destroy'])->name('personas.destroy');

    // Subscriber Management (Admin)
    Route::get('/subscribers', [SubscriberController::class, 'index'])->name('subscribers.index');
    Route::get('/subscribers/create', [SubscriberController::class, 'create'])->name('subscribers.create');
    Route::post('/subscribers', [SubscriberController::class, 'store'])->name('subscribers.store');
    Route::get('/subscribers/{subscriber}/edit', [SubscriberController::class, 'edit'])->name('subscribers.edit');
    Route::put('/subscribers/{subscriber}', [SubscriberController::class, 'update'])->name('subscribers.update');
    Route::delete('/subscribers/{subscriber}', [SubscriberController::class, 'destroy'])->name('subscribers.destroy');
    Route::post('/subscribers/{subscriber}/resend', [SubscriberController::class, 'resendVerification'])->name('subscribers.resend');
    Route::post('/subscribers/import', [SubscriberController::class, 'importCsv'])->name('subscribers.import');

    // App Settings
    Route::get('/settings', [AppSettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/general', [AppSettingsController::class, 'general'])->name('settings.general');
    Route::put('/settings/general', [AppSettingsController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('/settings/rating', [AppSettingsController::class, 'rating'])->name('settings.rating');
    Route::put('/settings/rating', [AppSettingsController::class, 'updateRating'])->name('settings.rating.update');

    // Theme Settings
    Route::get('/settings/themes', [\App\Http\Controllers\KnowledgeBoardThemeController::class, 'index'])->name('settings.themes');
    Route::get('/settings/themes/{knowledgeBoard}/edit', [\App\Http\Controllers\KnowledgeBoardThemeController::class, 'edit'])->name('settings.themes.edit');
    Route::put('/settings/themes/{knowledgeBoard}', [\App\Http\Controllers\KnowledgeBoardThemeController::class, 'update'])->name('settings.themes.update');
    Route::post('/settings/themes/{knowledgeBoard}/reset', [\App\Http\Controllers\KnowledgeBoardThemeController::class, 'reset'])->name('settings.themes.reset');
    Route::post('/settings/themes/select/{theme}', [\App\Http\Controllers\KnowledgeBoardThemeController::class, 'selectTheme'])->name('settings.themes.select');

    // App Configuration (Module Settings)
    Route::get('/configuration', [AppConfigurationController::class, 'index'])->name('configuration.index');
    Route::post('/configuration/feedback-settings', [AppConfigurationController::class, 'updateFeedbackSettings'])->name('configuration.feedback-settings.update');

    // Roadmap Management
    Route::get('/roadmap', [RoadmapController::class, 'index'])->name('roadmap.index');
    Route::post('/roadmap', [RoadmapController::class, 'store'])->name('roadmap.store');
    Route::put('/roadmap/{roadmap}', [RoadmapController::class, 'update'])->name('roadmap.update');
    Route::delete('/roadmap/{roadmap}', [RoadmapController::class, 'destroy'])->name('roadmap.destroy');
    Route::post('/roadmap/reorder', [RoadmapController::class, 'reorder'])->name('roadmap.reorder');
    Route::post('/roadmap/bulk-update', [RoadmapController::class, 'bulkUpdate'])->name('roadmap.bulkUpdate');

    // Feedback Category Management
    Route::get('/feedback-category', [FeedbackCategoryController::class, 'index'])->name('feedback-category.index');
    Route::post('/feedback-category', [FeedbackCategoryController::class, 'store'])->name('feedback-category.store');
    Route::put('/feedback-category/{feedbackCategory}', [FeedbackCategoryController::class, 'update'])->name('feedback-category.update');
    Route::delete('/feedback-category/{feedbackCategory}', [FeedbackCategoryController::class, 'destroy'])->name('feedback-category.destroy');
    Route::post('/feedback-category/reorder', [FeedbackCategoryController::class, 'reorder'])->name('feedback-category.reorder');
    Route::post('/feedback-category/bulk-update', [FeedbackCategoryController::class, 'bulkUpdate'])->name('feedback-category.bulkUpdate');

    // Feedback Management
    Route::get('/feedback', [FeedbackController::class, 'index'])->name('feedback.index');
    Route::get('/feedback/kanban', [FeedbackController::class, 'kanban'])->name('feedback.kanban');
    Route::post('/feedback/{feedback}/update-status', [FeedbackController::class, 'updateStatus'])->name('feedback.updateStatus');
    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
    Route::post('/feedback/{feedback}/comment', [FeedbackController::class, 'storeComment'])->name('feedback.comment.store');
    Route::delete('/feedback/{feedback}/comment/{comment}', [FeedbackController::class, 'destroyComment'])->name('feedback.comment.destroy');

    // Roadmap Items Management
    Route::get('/roadmap-items', [RoadmapItemController::class, 'index'])->name('roadmap-items.index');
    Route::get('/roadmap-items/kanban', [RoadmapItemController::class, 'kanban'])->name('roadmap-items.kanban');
    Route::post('/roadmap-items/{roadmapItem}/update-status', [RoadmapItemController::class, 'updateStatus'])->name('roadmap-items.updateStatus');
    Route::get('/roadmap-items/create', [RoadmapItemController::class, 'create'])->name('roadmap-items.create');
    Route::post('/roadmap-items', [RoadmapItemController::class, 'store'])->name('roadmap-items.store');
    Route::get('/roadmap-items/{roadmapItem}', [RoadmapItemController::class, 'show'])->name('roadmap-items.show');
    Route::get('/roadmap-items/{roadmapItem}/edit', [RoadmapItemController::class, 'edit'])->name('roadmap-items.edit');
    Route::put('/roadmap-items/{roadmapItem}', [RoadmapItemController::class, 'update'])->name('roadmap-items.update');
    Route::delete('/roadmap-items/{roadmapItem}', [RoadmapItemController::class, 'destroy'])->name('roadmap-items.destroy');
    Route::post('/roadmap-items/{roadmapItem}/comment', [RoadmapItemController::class, 'addComment'])->name('roadmap-items.comment.store');
    Route::delete('/roadmap-items/comment/{comment}', [RoadmapItemController::class, 'deleteComment'])->name('roadmap-items.comment.destroy');

    // Testimonials Management
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::get('/testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('/testimonials/{testimonial}', [TestimonialController::class, 'show'])->name('testimonials.show');
    Route::get('/testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Testimonial Templates Management
    Route::get('/testimonial-templates/create', [TestimonialTemplateController::class, 'create'])->name('testimonial-templates.create');
    Route::post('/testimonial-templates', [TestimonialTemplateController::class, 'store'])->name('testimonial-templates.store');
    Route::post('/testimonial-templates/send-test-email', [TestimonialTemplateController::class, 'sendTestEmail'])->name('testimonial-templates.send-test-email');
    Route::get('/testimonial-templates/{template}', [TestimonialTemplateController::class, 'show'])->name('testimonial-templates.show');
    Route::get('/testimonial-templates/{template}/edit', [TestimonialTemplateController::class, 'edit'])->name('testimonial-templates.edit');
    Route::put('/testimonial-templates/{template}', [TestimonialTemplateController::class, 'update'])->name('testimonial-templates.update');
    Route::delete('/testimonial-templates/{template}', [TestimonialTemplateController::class, 'destroy'])->name('testimonial-templates.destroy');

    // Testimonial Campaigns Management
    Route::get('/testimonial-campaigns', [\App\Http\Controllers\TestimonialCampaignController::class, 'index'])->name('testimonial-campaigns.index');
    Route::post('/testimonial-campaigns', [\App\Http\Controllers\TestimonialCampaignController::class, 'store'])->name('testimonial-campaigns.store');
    Route::get('/testimonial-campaigns/{campaign}/view', [\App\Http\Controllers\TestimonialCampaignController::class, 'view'])->name('testimonial-campaigns.view');
    Route::get('/testimonial-campaigns/{campaign}', [\App\Http\Controllers\TestimonialCampaignController::class, 'show'])->name('testimonial-campaigns.show');
    Route::put('/testimonial-campaigns/{campaign}', [\App\Http\Controllers\TestimonialCampaignController::class, 'update'])->name('testimonial-campaigns.update');
    Route::post('/testimonial-campaigns/{campaign}/pause', [\App\Http\Controllers\TestimonialCampaignController::class, 'pause'])->name('testimonial-campaigns.pause');
    Route::delete('/testimonial-campaigns/{campaign}', [\App\Http\Controllers\TestimonialCampaignController::class, 'destroy'])->name('testimonial-campaigns.destroy');
    Route::get('/testimonial-campaigns/{campaign}/statistics', [\App\Http\Controllers\TestimonialCampaignController::class, 'statistics'])->name('testimonial-campaigns.statistics');
    Route::post('/testimonial-campaigns/{campaign}/resend-failed', [\App\Http\Controllers\TestimonialCampaignController::class, 'resendFailedEmails'])->name('testimonial-campaigns.resend-failed');
});

require __DIR__.'/auth.php';

// Public Testimonial Form Routes (No Authentication Required)
Route::get('/testimonial/{uniqueUrl}', [TestimonialController::class, 'publicForm'])->name('testimonials.public.form');
Route::post('/testimonial/{uniqueUrl}', [TestimonialController::class, 'publicStore'])->name('testimonials.public.store');

// Mux Video API Routes (for video testimonials)
Route::prefix('api/mux')->group(function () {
    Route::post('/upload', [\App\Http\Controllers\Api\MuxVideoController::class, 'createUpload'])->name('api.mux.upload');
    Route::get('/upload/{uploadId}/status', [\App\Http\Controllers\Api\MuxVideoController::class, 'getUploadStatus'])->name('api.mux.upload.status');
    Route::post('/webhook', [\App\Http\Controllers\Api\MuxVideoController::class, 'handleWebhook'])->name('api.mux.webhook');
});

// Campaign Tracking Routes (No Authentication Required)
Route::get('/testimonial-campaign/open/{trackingToken}', [\App\Http\Controllers\TestimonialCampaignController::class, 'trackOpen'])->name('testimonials.campaign-open');
Route::get('/testimonial-campaign/click/{trackingToken}', [\App\Http\Controllers\TestimonialCampaignController::class, 'trackClick'])->name('testimonials.campaign-click');

// Public Pages - Using subdomain middleware for team detection
// These routes support both subdomain-based (team.example.com) and path-based (example.com/{unique_url}) routing
Route::middleware(['team.subdomain'])->group(function () {
    // Public Auth Routes
    Route::get('/{unique_url}/login', [PublicAuthController::class, 'showLoginForm'])->name('public.auth.login');
    Route::post('/{unique_url}/login', [PublicAuthController::class, 'sendMagicLink'])->name('public.auth.send-magic-link');
    Route::get('/{unique_url}/logout', [PublicAuthController::class, 'logout'])->name('public.auth.logout');

    // Public Content Routes
    Route::get('/{unique_url}/roadmap', [PublicController::class, 'roadmap'])->name('public.roadmap');
    Route::get('/{unique_url}/roadmap/{roadmapItem}', [PublicController::class, 'showRoadmapItem'])->name('public.roadmap.show');
    Route::get('/{unique_url}/changelog', [PublicController::class, 'changelog'])->name('public.changelog');
    Route::get('/{unique_url}/changelog/{changelog}', [PublicController::class, 'showChangelog'])->name('public.changelog.show');
    Route::get('/{unique_url}/testimonials', [PublicController::class, 'testimonials'])->name('public.testimonials');
    Route::get('/{unique_url}/testimonials/{testimonial}', [PublicController::class, 'showTestimonial'])->name('public.testimonials.show');
    Route::get('/{unique_url}/knowledge', [PublicController::class, 'knowledge'])->name('public.knowledge');
    Route::get('/{unique_url}/knowledge/{knowledgeBoard}', [PublicController::class, 'showKnowledge'])->name('public.knowledge.show');
    Route::get('/{unique_url}/knowledge/{knowledgeBoard}/category/{category}', [PublicController::class, 'showKnowledgeCategory'])->name('public.knowledge.category');
    Route::get('/{unique_url}/knowledge/{knowledgeBoard}/article/{article}', [PublicController::class, 'showKnowledgeArticle'])->name('public.knowledge.article');
    Route::get('/{unique_url}/knowledge/{knowledgeBoard}/search', [PublicController::class, 'searchKnowledgeArticles'])->name('public.knowledge.search');
    Route::get('/{unique_url}/subscribe', [PublicController::class, 'subscribe'])->name('public.subscribe');
    Route::post('/{unique_url}/subscribe', [PublicController::class, 'subscribeSubmit'])->name('public.subscribe.submit');

    // Public Feedback Submission and Voting
    Route::post('/{unique_url}/feedback/submit', [PublicController::class, 'submitFeedback'])->name('public.feedback.submit');
    Route::get('/{unique_url}/feedback/{feedback}', [PublicController::class, 'showFeedback'])->name('public.feedback.show');
    Route::post('/{unique_url}/feedback/{feedback}/vote', [PublicController::class, 'vote'])->name('public.feedback.vote');
    Route::post('/{unique_url}/feedback/{feedback}/unvote', [PublicController::class, 'unvote'])->name('public.feedback.unvote');
    Route::post('/{unique_url}/feedback/{feedback}/request-otp', [PublicController::class, 'requestVoteOtp'])->name('public.feedback.request-otp');
    Route::post('/{unique_url}/feedback/{feedback}/verify-otp', [PublicController::class, 'verifyVoteOtp'])->name('public.feedback.verify-otp');
    Route::post('/{unique_url}/feedback/{feedback}/comment', [PublicController::class, 'storePublicComment'])->name('public.feedback.comment');

    // Public Rating Submission
    Route::post('/{unique_url}/rating/submit', [PublicController::class, 'submitRating'])->name('public.rating.submit');
    Route::post('/{unique_url}/rating/comment', [PublicController::class, 'submitRatingComment'])->name('public.rating.comment');
    Route::get('/{unique_url}/ratings', [PublicController::class, 'getRatings'])->name('public.ratings.get');

    // Public Home (must be last within group)
    Route::get('/{unique_url}', [PublicController::class, 'home'])->name('public.home');
});
