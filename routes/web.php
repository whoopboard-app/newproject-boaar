<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChangelogController;
use App\Http\Controllers\AppSettingsController;
use App\Http\Controllers\KnowledgeBoardController;
use App\Http\Controllers\BoardCategoryController;
use App\Http\Controllers\BoardArticleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Team Management
    Route::get('/team', function () {
        return view('team.manage');
    })->name('team.manage');

    // Category Management
    Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('categories.manage');
    Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('categories.destroy');

    // Changelog Management
    Route::get('/changelog', [ChangelogController::class, 'index'])->name('changelog.index');
    Route::get('/changelog/create', [ChangelogController::class, 'create'])->name('changelog.create');
    Route::post('/changelog', [ChangelogController::class, 'store'])->name('changelog.store');

    // Knowledge Board Management
    Route::get('/knowledge-board', [KnowledgeBoardController::class, 'index'])->name('knowledge-board.index');
    Route::get('/knowledge-board/create', [KnowledgeBoardController::class, 'create'])->name('knowledge-board.create');
    Route::post('/knowledge-board', [KnowledgeBoardController::class, 'store'])->name('knowledge-board.store');
    Route::get('/knowledge-board/{knowledgeBoard}', [KnowledgeBoardController::class, 'show'])->name('knowledge-board.show');

    // Board Category Management
    Route::get('/knowledge-board/{knowledgeBoard}/category/create', [BoardCategoryController::class, 'create'])->name('board-category.create');
    Route::post('/knowledge-board/{knowledgeBoard}/category', [BoardCategoryController::class, 'store'])->name('board-category.store');

    // Board Article Management
    Route::get('/knowledge-board/{knowledgeBoard}/article/create', [BoardArticleController::class, 'create'])->name('board-article.create');
    Route::post('/knowledge-board/{knowledgeBoard}/article', [BoardArticleController::class, 'store'])->name('board-article.store');

    // App Settings
    Route::get('/settings', [AppSettingsController::class, 'index'])->name('settings.index');
});

require __DIR__.'/auth.php';
