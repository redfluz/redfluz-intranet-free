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
use App\Http\Requests\News\NewsCategory\StoreNewsCategoryRequest;
use App\Http\Requests\News\NewsCategory\UpdateNewsCategoryRequest;
use App\Models\News\NewsCategory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use function is_string;

class NewsCategoryController extends Controller
{
    /**
     * Display a listing of the news categories.
     *
     * @param  Request  $request  The HTTP request instance.
     * @return View Returns the rendered Blade view.
     */
    public function index(Request $request): View
    {
        $this->allows(FileResources::NEWS_CATEGORIES);

        $search = $request->input('search');

        $query = NewsCategory::query();

        if (is_string($search) && $search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%");
            });
        }

        $newsCategories = $query
            ->latest()
            ->paginate(self::PER_PAGE_GRID_NORMAL)
            ->withQueryString();

        return view('news.news-categories.index', compact('newsCategories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View Returns the rendered Blade view.
     */
    public function create(): View
    {
        $this->allows(FileResources::NEWS_CATEGORIES, FileAction::ADD);

        return view('news.news-categories.create');
    }

    /**
     * Handle the incoming request to create a new resource.
     *
     * @param  StoreNewsCategoryRequest  $request  The validated request instance.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function store(StoreNewsCategoryRequest $request): RedirectResponse
    {
        /** @var array{name: string, description: ?string} $validated */
        $validated = $request->validated();

        NewsCategory::query()
            ->create([
                'name' => $validated['name'],
                'description' => $validated['description'],
            ]);

        return redirect()
            ->route('news.news-categories.index')
            ->with('success', FlashMessage::created(FileResources::NEWS_CATEGORIES));
    }

    /**
     * Show the form for editing an open resource.
     *
     * @param  NewsCategory  $newsCategory  The news category resource instance.
     * @return View Returns the rendered Blade view.
     */
    public function edit(NewsCategory $newsCategory): View
    {
        $this->allows(FileResources::NEWS_CATEGORIES, FileAction::UPDATE);

        return view('news.news-categories.edit', compact('newsCategory'));
    }

    /**
     * Update an existing resource by its object.
     *
     * @param  UpdateNewsCategoryRequest  $request  The validated request instance.
     * @param  NewsCategory  $newsCategory  The news category resource instance.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function update(UpdateNewsCategoryRequest $request, NewsCategory $newsCategory): RedirectResponse
    {
        /** @var array{name: string, description: ?string} $validated */
        $validated = $request->validated();

        $newsCategory->name = $validated['name'];
        $newsCategory->description = $validated['description'];
        $newsCategory->save();

        return redirect()
            ->route('news.news-categories.index')
            ->with('success', FlashMessage::updated(FileResources::NEWS_CATEGORIES));
    }

    /**
     * Remove a resource by its object.
     *
     * @param  NewsCategory  $newsCategory  The news category resource instance.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function destroy(NewsCategory $newsCategory): RedirectResponse
    {
        $this->allows(FileResources::NEWS_CATEGORIES, FileAction::REMOVE);

        $newsCategory->delete();

        return redirect()
            ->route('news.news-categories.index')
            ->with('success', FlashMessage::deleted(FileResources::NEWS_CATEGORIES));
    }
}
