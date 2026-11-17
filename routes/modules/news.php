<?php

/**
 * ============================================================================
 *
 * @author      redfluz
 *
 * @github      https://github.com/redfluz
 *
 * @youtube     https://youtube.com/@redfluz
 *
 * @contact     corex2020@icloud.com
 *
 * @customer
 *
 * @copyright   Copyright (c) 2026 redfluz. All Rights Reserved.
 * ============================================================================
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */

use App\Http\Controllers\News\NewsCategoryController;
use App\Http\Controllers\News\NewsCommentController;
use App\Http\Controllers\News\NewsController;
use Illuminate\Support\Facades\Route;

Route::get('news', [NewsController::class, 'index'])->name('news.news.index');
Route::get('news/create', [NewsController::class, 'create'])->name('news.news.create');
Route::post('news', [NewsController::class, 'store'])->name('news.news.store');
Route::get('news/{news}', [NewsController::class, 'show'])->name('news.news.show');
Route::get('news/{news}/edit', [NewsController::class, 'edit'])->name('news.news.edit');
Route::put('news/{news}', [NewsController::class, 'update'])->name('news.news.update');
Route::delete('news/{news}', [NewsController::class, 'destroy'])->name('news.news.destroy');

Route::get('news-categories', [NewsCategoryController::class, 'index'])->name('news.news-categories.index');
Route::get('news-categories/create', [NewsCategoryController::class, 'create'])->name('news.news-categories.create');
Route::post('news-categories', [NewsCategoryController::class, 'store'])->name('news.news-categories.store');
Route::get('news-categories/{newsCategory}/edit', [NewsCategoryController::class, 'edit'])->name('news.news-categories.edit');
Route::put('news-categories/{newsCategory}', [NewsCategoryController::class, 'update'])->name('news.news-categories.update');
Route::delete('news-categories/{newsCategory}', [NewsCategoryController::class, 'destroy'])->name('news.news-categories.destroy');

Route::post('comments', [NewsCommentController::class, 'store'])->name('news.comments.store');
Route::delete('comments/{comment}', [NewsCommentController::class, 'destroy'])->name('news.comments.destroy');
