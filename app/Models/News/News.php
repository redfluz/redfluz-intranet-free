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

namespace App\Models\News;

use App\Enums\News\NewsStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @mixin Builder<News>
 *
 * @property string $id
 * @property string $news_category_id
 * @property string $title
 * @property string $short_description
 * @property string $content
 * @property string $featured_image
 * @property NewsStatus $status
 * @property int $views_count
 * @property CarbonInterface|null $published_at
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @noinspection PhpUnused
 *
 * @property-read NewsCategory|null $category
 * @property-read Collection<int, NewsComment> $comments
 * @property-read Collection<int, Activity> $activities
 * @property-read Collection<int, Activity> $activitiesAsCauser
 * @property-read Collection<int, Activity> $activitiesAsSubject
 */
#[Table('news', key: 'id')]
#[Fillable([
    'news_category_id',
    'title',
    'short_description',
    'content',
    'featured_image',
    'status',
    'views_count',
    'published_at',
])]
class News extends Model
{
    use HasActivity, HasUuids, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => NewsStatus::class,
            'views_count' => 'integer',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Resolve the featured image to a displayable URL.
     *
     * New uploads are stored on the public disk as a relative path, while older
     * demo rows may still hold an absolute external URL. Both must render, so an
     * absolute URL is returned as-is and a stored path is resolved through the
     * public disk.
     *
     * @return string|null The image URL, or null when no image is set.
     */
    public function featuredImageUrl(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http://') || str_starts_with($this->featured_image, 'https://')) {
            return $this->featured_image;
        }

        return Storage::disk('public')->url($this->featured_image);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the news category that the news belongs to.
     *
     * @return BelongsTo<NewsCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategory::class, 'news_category_id');
    }

    /**
     * Define a one-to-many relationship.
     * Get the top-level comments associated with the news.
     *
     * @return HasMany<NewsComment, $this>
     */
    public function comments(): HasMany
    {
        return $this->hasMany(NewsComment::class)
            ->whereNull('parent_id')
            ->with(['user', 'replies'])
            ->latest();
    }

    /**
     * Get the options for logging activity using Spatie Activitylog.
     *
     * @return LogOptions Returns configured log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->logOnlyDirty();
    }
}
