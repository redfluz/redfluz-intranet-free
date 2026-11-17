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

namespace App\Http\Requests\News\News;

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * The fields of a news item, named.
 *
 * Creating and editing a news item ask for the same fields but not quite the
 * same ones, so each states its own rules rather than one inheriting from the
 * other: a rule inherited and then removed is a rule nobody can read in one
 * place. What they do share is the naming of the fields, which is here, so a
 * controller asking for the title gets the same field whichever request it
 * was handed.
 *
 * The form posts published_at + published_time as separate date and time
 * inputs. prepareForValidation() merges them into the datetime string that
 * the date validation rule expects, mirroring what the controller previously
 * did via $request->merge() before calling validate().
 */
abstract class NewsRequest extends BaseFormRequest
{
    use ChecksFilePermission;

    /**
     * Merge the separate date and time inputs into a single datetime string
     * before the validation rules are applied.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('published_at') && $this->filled('published_time')) {
            $publishedAt = $this->input('published_at');
            $publishedTime = $this->input('published_time');
            if (is_string($publishedAt) && is_string($publishedTime)) {
                $this->merge(['published_at' => $publishedAt.' '.$publishedTime]);
            }
        }
    }

    /**
     * The title of the news item.
     */
    public function title(): string
    {
        return $this->stringOrEmpty('title');
    }

    /**
     * The category the news item belongs to, if supplied.
     */
    public function newsCategoryId(): ?string
    {
        return $this->stringOrNull('news_category_id');
    }

    /**
     * The short description, if supplied.
     */
    public function shortDescription(): ?string
    {
        return $this->stringOrNull('short_description');
    }

    /**
     * The full content of the news item.
     */
    public function content(): string
    {
        return $this->stringOrEmpty('content');
    }

    /**
     * The featured image URL, if supplied.
     */
    public function featuredImage(): ?string
    {
        return $this->stringOrNull('featured_image');
    }

    /**
     * The publication status of the news item.
     */
    public function statusValue(): string
    {
        return $this->stringOrEmpty('status');
    }

    /**
     * The publication timestamp, if supplied.
     */
    public function publishedAt(): ?string
    {
        return $this->stringOrNull('published_at');
    }
}
