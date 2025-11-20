<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\KnowledgeBoardController;
use App\Http\Controllers\BoardCategoryController;
use App\Http\Controllers\BoardArticleController;
use App\Http\Controllers\UserSegmentController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\FeedbackCategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\TeamInvitationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Public Subscriber Routes (No Authentication Required)
Route::post('/subscribe', [SubscriberController::class, 'subscribe'])->name('subscriber.subscribe');
Route::get('/verify/{token}', [SubscriberController::class, 'verify'])->name('subscriber.verify');
Route::get('/unsubscribe/{token}', [SubscriberController::class, 'unsubscribe'])->name('subscriber.unsubscribe');

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
    Route::get('/feedback/create', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
    Route::get('/feedback/{feedback}', [FeedbackController::class, 'show'])->name('feedback.show');
    Route::get('/feedback/{feedback}/edit', [FeedbackController::class, 'edit'])->name('feedback.edit');
    Route::put('/feedback/{feedback}', [FeedbackController::class, 'update'])->name('feedback.update');
    Route::delete('/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('feedback.destroy');
    Route::post('/feedback/{feedback}/comment', [FeedbackController::class, 'storeComment'])->name('feedback.comment.store');
    Route::delete('/feedback/{feedback}/comment/{comment}', [FeedbackController::class, 'destroyComment'])->name('feedback.comment.destroy');
});

require __DIR__.'/auth.php';
