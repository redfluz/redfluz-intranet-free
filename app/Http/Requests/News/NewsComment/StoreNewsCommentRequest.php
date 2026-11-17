<?php

/**
 * ============================================================================
 *
 * @author      redfluz
 *
 * @github      https://github.com/redfluz
 *
 * @youtube     https://www.youtube.com/@redfluz
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

declare(strict_types=1);

namespace App\Http\Requests\News\NewsComment;

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * The fields a news comment is created from.
 *
 * The rules live here rather than in the controller so that the controller can
 * be handed a request that has already been checked. A rule stated once cannot
 * be stated twice in two places that then disagree.
 */
class StoreNewsCommentRequest extends BaseFormRequest
{
    use ChecksFilePermission;

    /**
     * Who may create a news comment.
     *
     * The controller does not guard this action with a file permission check; it
     * only requires a signed-in user and aborts with a 401 otherwise. That check
     * stays in the controller, so this request does not add a file permission
     * check of its own.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'news_id' => 'required|exists:news,id',
            'parent_id' => 'nullable|exists:news_comments,id',
            'comment' => 'required|string|max:1000',
        ];
    }
}
