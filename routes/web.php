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
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\Backoffice\BackofficeAuthController;
use App\Http\Controllers\Backoffice\BackofficeDashboardController;
use App\Http\Controllers\Backoffice\BackofficeClientController;
use Illuminate\Support\Facades\Route;

// =============================================================================
// Backoffice Admin Portal Routes
// =============================================================================
Route::prefix('backoffice')->name('backoffice.')->group(function () {
    // Guest routes (not logged into backoffice)
    Route::get('/', [BackofficeAuthController::class, 'showEmailForm'])->name('email');
    Route::post('/send-code', [BackofficeAuthController::class, 'sendCode'])->name('send-code');
    Route::get('/login', [BackofficeAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [BackofficeAuthController::class, 'login'])->name('authenticate');

    // Protected routes (logged into backoffice)
    Route::middleware('backoffice.auth')->group(function () {
        Route::get('/dashboard', [BackofficeDashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [BackofficeAuthController::class, 'logout'])->name('logout');

        // Clients Management
        Route::get('/clients', [BackofficeClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/{client}', [BackofficeClientController::class, 'show'])->name('clients.show');
    });
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

    // Site Access Invites
    Route::post('/settings/site-access', [AppSettingsController::class, 'addSiteAccessInvite'])->name('settings.site-access.add');
    Route::delete('/settings/site-access/{invite}', [AppSettingsController::class, 'removeSiteAccessInvite'])->name('settings.site-access.remove');

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
    Route::get('/roadmaps', [RoadmapController::class, 'index'])->name('roadmap.index');
    Route::post('/roadmaps', [RoadmapController::class, 'store'])->name('roadmap.store');
    Route::post('/roadmaps/reorder', [RoadmapController::class, 'reorder'])->name('roadmap.reorder');
    Route::post('/roadmaps/bulk-update', [RoadmapController::class, 'bulkUpdate'])->name('roadmap.bulkUpdate');
    Route::delete('/roadmaps/workflow', [RoadmapController::class, 'destroyWorkflow'])->name('roadmap.destroyWorkflow');
    Route::put('/roadmaps/{roadmap}', [RoadmapController::class, 'update'])->name('roadmap.update');
    Route::delete('/roadmaps/{roadmap}', [RoadmapController::class, 'destroy'])->name('roadmap.destroy');

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

    // Survey Management
    Route::get('/surveys', [SurveyController::class, 'index'])->name('surveys.index');
    Route::get('/surveys/create', [SurveyController::class, 'create'])->name('surveys.create');
    Route::get('/surveys/builder/{type}', [SurveyController::class, 'builder'])->name('surveys.builder');
    Route::post('/surveys', [SurveyController::class, 'store'])->name('surveys.store');
    Route::get('/surveys/{survey}', [SurveyController::class, 'show'])->name('surveys.show');
    Route::get('/surveys/{survey}/edit', [SurveyController::class, 'edit'])->name('surveys.edit');
    Route::put('/surveys/{survey}', [SurveyController::class, 'update'])->name('surveys.update');
    Route::delete('/surveys/{survey}', [SurveyController::class, 'destroy'])->name('surveys.destroy');
    Route::post('/surveys/{survey}/toggle-active', [SurveyController::class, 'toggleActive'])->name('surveys.toggle-active');
    Route::get('/surveys/{survey}/install', [SurveyController::class, 'install'])->name('surveys.install');
    Route::get('/surveys/{survey}/responses', [SurveyController::class, 'responses'])->name('surveys.responses');
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

// Public Survey API (No Authentication Required)
Route::get('/api/survey/{survey}/config', [SurveyController::class, 'getConfig'])->name('api.survey.config');
Route::post('/api/survey/{survey}/respond', [SurveyController::class, 'submitResponse'])->name('api.survey.respond');

// Campaign Tracking Routes (No Authentication Required)
Route::get('/testimonial-campaign/open/{trackingToken}', [\App\Http\Controllers\TestimonialCampaignController::class, 'trackOpen'])->name('testimonials.campaign-open');
Route::get('/testimonial-campaign/click/{trackingToken}', [\App\Http\Controllers\TestimonialCampaignController::class, 'trackClick'])->name('testimonials.campaign-click');

// =============================================================================
// Subdomain-based Public Routes (e.g., demo.wbapp.com:8000)
// =============================================================================
// IMPORTANT: These routes MUST be at the END of the file so they are registered LAST.
// This prevents them from conflicting with admin routes that have the same paths
// (e.g., /feedback, /roadmap, /changelog, /testimonials).
//
// The SubdomainRouting middleware sets 'is_subdomain' attribute when a valid subdomain
// is detected. These routes check that attribute and only respond on subdomain requests.
// On non-subdomain requests, they return 404, allowing the admin routes (registered first)
// to handle those paths with proper auth middleware.
// =============================================================================
Route::middleware(['web'])->group(function () {
    Route::get('/', function (\Illuminate\Http\Request $request) {
        // Check if this is a subdomain request
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->home($request);
        }
        // Default: redirect to login
        return redirect()->route('login');
    });

    // Public pages - only accessible via subdomain
    Route::get('/ideas', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->home($request);
        }
        abort(404);
    })->name('public.feedback');

    Route::post('/ideas/submit', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->submitFeedback($request);
        }
        abort(404);
    })->name('public.feedback.submit');

    Route::get('/ideas/{feedback}', function (\Illuminate\Http\Request $request, $feedback) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->showFeedback($request, $feedback);
        }
        abort(404);
    })->name('public.feedback.show');

    Route::post('/ideas/{feedback}/vote', function (\Illuminate\Http\Request $request, $feedback) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->vote($request, $feedback);
        }
        abort(404);
    })->name('public.feedback.vote');

    Route::post('/ideas/{feedback}/unvote', function (\Illuminate\Http\Request $request, $feedback) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->unvote($request, $feedback);
        }
        abort(404);
    })->name('public.feedback.unvote');

    Route::post('/ideas/{feedback}/request-otp', function (\Illuminate\Http\Request $request, $feedback) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->requestVoteOtp($request, $feedback);
        }
        abort(404);
    })->name('public.feedback.request-otp');

    Route::post('/ideas/{feedback}/verify-otp', function (\Illuminate\Http\Request $request, $feedback) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->verifyVoteOtp($request, $feedback);
        }
        abort(404);
    })->name('public.feedback.verify-otp');

    Route::post('/ideas/{feedback}/comment', function (\Illuminate\Http\Request $request, $feedback) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->storePublicComment($request, $feedback);
        }
        abort(404);
    })->name('public.feedback.comment');

    Route::get('/roadmap', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->roadmap($request);
        }
        abort(404);
    })->name('public.roadmap');

    Route::get('/roadmap/{roadmapItem}', function (\Illuminate\Http\Request $request, $roadmapItem) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->showRoadmapItem($request, $roadmapItem);
        }
        abort(404);
    })->name('public.roadmap.show');

    Route::get('/updates', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->changelog($request);
        }
        abort(404);
    })->name('public.changelog');

    Route::get('/updates/{changelog}', function (\Illuminate\Http\Request $request, $changelog) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->showChangelog($request, $changelog);
        }
        abort(404);
    })->name('public.changelog.show');

    Route::get('/reviews', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->testimonials($request);
        }
        abort(404);
    })->name('public.testimonials');

    Route::get('/reviews/{testimonial}', function (\Illuminate\Http\Request $request, $testimonial) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->showTestimonial($request, $testimonial);
        }
        abort(404);
    })->name('public.testimonials.show');

    Route::get('/knowledge', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->knowledge($request);
        }
        abort(404);
    })->name('public.knowledge');

    Route::get('/knowledge/{knowledgeBoard}', function (\Illuminate\Http\Request $request, $knowledgeBoard) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->showKnowledge($request, $knowledgeBoard);
        }
        abort(404);
    })->name('public.knowledge.show');

    Route::get('/knowledge/{knowledgeBoard}/category/{category}', function (\Illuminate\Http\Request $request, $knowledgeBoard, $category) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->showKnowledgeCategory($request, $knowledgeBoard, $category);
        }
        abort(404);
    })->name('public.knowledge.category');

    Route::get('/knowledge/{knowledgeBoard}/article/{article}', function (\Illuminate\Http\Request $request, $knowledgeBoard, $article) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->showKnowledgeArticle($request, $knowledgeBoard, $article);
        }
        abort(404);
    })->name('public.knowledge.article');

    Route::get('/knowledge/{knowledgeBoard}/search', function (\Illuminate\Http\Request $request, $knowledgeBoard) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->searchKnowledgeArticles($request, $knowledgeBoard);
        }
        abort(404);
    })->name('public.knowledge.search');

    Route::get('/subscribe', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->subscribe($request);
        }
        abort(404);
    })->name('public.subscribe');

    Route::post('/subscribe', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->subscribeSubmit($request);
        }
        abort(404);
    })->name('public.subscribe.submit');

    // Public Rating Submission
    Route::post('/rating/submit', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->submitRating($request);
        }
        abort(404);
    })->name('public.rating.submit');

    Route::post('/rating/comment', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->submitRatingComment($request);
        }
        abort(404);
    })->name('public.rating.comment');

    Route::get('/ratings', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicController::class)->getRatings($request);
        }
        abort(404);
    })->name('public.ratings.get');

    // Public home route (alias for feedback)
    Route::get('/home', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(SubdomainPublicController::class)->home($request);
        }
        abort(404);
    })->name('public.home');

    // Public Auth Routes (using /auth prefix to avoid conflict with admin /login)
    Route::get('/auth/login', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicAuthController::class)->showLoginForm($request);
        }
        // Fallback to admin login
        return redirect()->route('login');
    })->name('public.auth.login');

    Route::post('/auth/login', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicAuthController::class)->sendMagicLink($request);
        }
        abort(404);
    })->name('public.auth.send-magic-link');

    Route::get('/auth/logout', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(PublicAuthController::class)->logout($request);
        }
        abort(404);
    })->name('public.auth.logout');

    // Site Access Routes (for private sites)
    Route::get('/access', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(\App\Http\Controllers\SiteAccessController::class)->showLoginForm($request);
        }
        abort(404);
    })->name('public.access.login');

    Route::post('/access/request-code', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(\App\Http\Controllers\SiteAccessController::class)->requestCode($request);
        }
        abort(404);
    })->name('public.access.request-code');

    Route::get('/access/verify', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(\App\Http\Controllers\SiteAccessController::class)->showVerifyForm($request);
        }
        abort(404);
    })->name('public.access.verify-form');

    Route::post('/access/verify', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(\App\Http\Controllers\SiteAccessController::class)->verifyCode($request);
        }
        abort(404);
    })->name('public.access.verify');

    Route::post('/access/resend-code', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(\App\Http\Controllers\SiteAccessController::class)->resendCode($request);
        }
        abort(404);
    })->name('public.access.resend-code');

    Route::get('/access/logout', function (\Illuminate\Http\Request $request) {
        if ($request->attributes->get('is_subdomain')) {
            return app(\App\Http\Controllers\SiteAccessController::class)->logout($request);
        }
        abort(404);
    })->name('public.access.logout');
});
