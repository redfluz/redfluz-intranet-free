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
use App\Enums\FileResources;
use App\Http\Controllers\Controller;
use App\Http\Requests\News\NewsComment\StoreNewsCommentRequest;
use App\Models\News\NewsComment;
use Illuminate\Http\JsonResponse;

class NewsCommentController extends Controller
{
    /**
     * Handle the incoming request to create a new resource.
     *
     * Validates the incoming request data and stores a new record in the database.
     *
     * @param  StoreNewsCommentRequest  $request  The validated request instance.
     * @return JsonResponse The HTTP request instance containing input data.
     */
    public function store(StoreNewsCommentRequest $request): JsonResponse
    {
        $user = $request->user();
        abort_if($user === null, 401);

        $validated = $request->validated();

        $comment = NewsComment::query()
            ->create([
                'news_id' => $validated['news_id'],
                'user_id' => $user->id,
                'parent_id' => $validated['parent_id'] ?? null,
                'comment' => $validated['comment'],
            ]);

        $comment->load('user');

        //  if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => __('news_comments.message.posted'),
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'user_name' => $user->name,
                'user_id' => $comment->user_id,
                'created_at' => $comment->created_at->diffForHumans(),
                'parent_id' => $comment->parent_id,
            ],
        ]);
        //  }

        // / return back()->with('success', FlashMessage::created(FileResources::NEWS_COMMENTS));
    }

    /**
     * Remove a resource by its object.
     * Delete a comment via AJAX (Author only).
     *
     * @param  NewsComment  $comment  The news resource instance.
     * @return JsonResponse JSON response confirming deletion or returning an error if not found.
     */
    public function destroy(NewsComment $comment): JsonResponse
    {
        if ($comment->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.',
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => FlashMessage::deleted(FileResources::NEWS_COMMENTS),
        ]);
    }
}
