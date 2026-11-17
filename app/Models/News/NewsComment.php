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

use App\Models\System\User;
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
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @mixin Builder<NewsComment>
 *
 * @property string $id
 * @property string $news_id
 * @property string $user_id
 * @property string|null $parent_id
 * @property string $comment
 * @property int $likes_count
 * @property int $unlikes_count
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @noinspection PhpUnused
 *
 * @property-read User|null $user
 * @property-read News|null $news
 * @property-read NewsComment|null $parent
 * @property-read Collection<int, NewsComment> $replies
 * @property-read Collection<int, NewsCommentLike> $likes
 * @property-read Collection<int, Activity> $activities
 * @property-read Collection<int, Activity> $activitiesAsCauser
 * @property-read Collection<int, Activity> $activitiesAsSubject
 */
#[Table('news_comments', key: 'id')]
#[Fillable(['news_id', 'user_id', 'parent_id', 'comment', 'likes_count', 'unlikes_count'])]
class NewsComment extends Model
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
            'likes_count' => 'integer',
            'unlikes_count' => 'integer',
        ];
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user who made the comment.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the news article that the comment belongs to.
     *
     * @return BelongsTo<News, $this>
     */
    public function news(): BelongsTo
    {
        return $this->belongsTo(News::class);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the parent comment that the reply belongs to.
     *
     * @return BelongsTo<NewsComment, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(NewsComment::class, 'parent_id');
    }

    /**
     * Define a one-to-many relationship.
     * Get the replies associated with the comment.
     *
     * @return HasMany<NewsComment, $this>
     */
    public function replies(): HasMany
    {
        return $this->hasMany(NewsComment::class, 'parent_id')->with('user', 'replies');
    }

    /**
     * Define a one-to-many relationship.
     * Get the likes and unlikes associated with the comment.
     *
     * @return HasMany<NewsCommentLike, $this>
     */
    public function likes(): HasMany
    {
        return $this->hasMany(NewsCommentLike::class, 'comment_id');
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
