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

namespace App\Http\Controllers\News;

use App\Constants\FlashMessage;
use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Http\Controllers\Controller;
use App\Http\Requests\News\News\StoreNewsRequest;
use App\Http\Requests\News\News\UpdateNewsRequest;
use App\Models\News\News;
use App\Models\News\NewsCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View Returns the rendered Blade view.
     */
    public function index(): View
    {
        $this->allows(FileResources::NEWS);

        $newsList = News::query()
            ->with('category')
            ->latest('published_at')
            ->paginate(self::PER_PAGE_GRID);

        return view('news.news.index', compact('newsList'));
    }

    /**
     * Show the form for creating a new resource.
     * Show the form for creating a new resource.
     *
     * @return View Returns the rendered Blade view.
     */
    public function create(): View
    {
        $this->allows(FileResources::NEWS, FileAction::ADD);

        $newsCategories = NewsCategory::all();

        return view('news.news.create', compact('newsCategories'));
    }

    /**
     * Handle the incoming request to create a new resource.
     *
     * Validates the incoming request data and stores a new record in the database.
     *
     * @param  StoreNewsRequest  $request  The validated request instance containing form data.
     * @return RedirectResponse Redirects to the index route with a flash message.
     */
    public function store(StoreNewsRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        unset($validated['featured_image']);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('news/featured', 'public') ?: null;
        }

        News::query()->create($validated);

        return redirect()
            ->route('news.news.index')
            ->with('success', FlashMessage::created(FileResources::NEWS));
    }

    /**
     * Display the specified resource.
     * Retrieve and display a specific resource by its model instance.
     *
     * @param  News  $news  The news resource instance.
     * @return View Returns the rendered Blade view.
     */
    public function show(News $news): View
    {
        // $this->redirectIfDenied(FileResources::NEWS);

        $news->increment('views_count');

        $news->load(['category', 'comments.user', 'comments.replies.user']);

        return view('news.news.show', compact('news'));
    }

    /**
     * Show the form for editing an open resource.
     *
     * @param  News  $news  The news resource instance.
     * @return View Returns the rendered Blade view.
     */
    public function edit(News $news): View
    {
        $this->allows(FileResources::NEWS, FileAction::UPDATE);

        $newsCategories = NewsCategory::all();

        return view('news.news.edit', compact('news', 'newsCategories'));
    }

    /**
     * Update an existing resource by its object.
     *
     * @param  UpdateNewsRequest  $request  The validated request instance containing updated data.
     * @param  News  $news  The news resource instance.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function update(UpdateNewsRequest $request, News $news): RedirectResponse
    {
        $validated = $request->validated();
        unset($validated['featured_image']);

        if ($request->hasFile('featured_image')) {
            $this->deleteStoredImage($news->featured_image);
            $validated['featured_image'] = $request->file('featured_image')->store('news/featured', 'public') ?: null;
        }

        $news->update($validated);

        return redirect()
            ->route('news.news.index')
            ->with('success', FlashMessage::updated(FileResources::NEWS));
    }

    /**
     * Remove a resource by its object.
     *
     * @param  News  $news  The news resource instance.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function destroy(News $news): RedirectResponse
    {
        $this->allows(FileResources::NEWS, FileAction::REMOVE);

        $this->deleteStoredImage($news->featured_image);

        $news->delete();

        return redirect()
            ->route('news.news.index')
            ->with('success', FlashMessage::deleted(FileResources::NEWS));
    }

    /**
     * Delete a locally stored featured image, if any.
     *
     * Legacy demo rows may hold an absolute external URL rather than a stored
     * path; those are left untouched because there is no local file to remove.
     *
     * @param  string|null  $path  The stored image path or external URL.
     * @return void Does not return a value.
     */
    private function deleteStoredImage(?string $path): void
    {
        if (! $path || str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return;
        }

        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
