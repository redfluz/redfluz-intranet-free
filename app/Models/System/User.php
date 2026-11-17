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

namespace App\Models\System;

use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Models\Management\Department;
use App\Models\Management\Organization;
use App\Models\Menu\PagePermission;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @mixin Builder<User>
 *
 * @property string $id
 * @property string|null $uuid
 * @property string|null $role_id
 * @property string $name
 * @property string $email
 * @property string $locale
 * @property string $theme
 * @property string|null $department_id
 * @property string|null $organization_id
 * @property string $picture
 * @property string|null $background_image
 * @property string $password
 * @property bool $must_change_password
 * @property bool $is_employee
 * @property string|null $remember_token
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface|null $email_verified_at
 * @property-read Role|null $role
 * @property-read Department|null $department
 * @property-read Organization|null $organization
 * @property-read Collection<int, Notification> $notifications
 * @property-read Collection<int, Notification> $unreadNotifications
 *
 * @noinspection PhpUnused
 *
 * @property-read Collection<int, Activity> $activities
 * @property-read Collection<int, Activity> $activitiesAsCauser
 * @property-read Collection<int, Activity> $activitiesAsSubject
 */
#[Table('users', key: 'id')]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasActivity, HasApiTokens, HasUuids, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'must_change_password',
        'theme',
        'background_image',
        'department_id',
        'organization_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'role_id' => 'string',
            'department_id' => 'string',
            'organization_id' => 'string',
            'name' => 'string',
            'email' => 'string',
            'locale' => 'string',
            'theme' => 'string',
            'picture' => 'string',
            'background_image' => 'string',
            'password' => 'hashed',
            'must_change_password' => 'boolean',
            'is_employee' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the role that the user belongs to.
     *
     * @return BelongsTo<Role, $this>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the department that the user belongs to.
     *
     * @return BelongsTo<Department, $this>
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the organisation-chart position assigned to the user.
     *
     * @return BelongsTo<Organization, $this>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Get the entity's notifications using the custom Notification model.
     *
     * @return MorphMany<Notification, $this>
     */
    public function notifications(): MorphMany
    {
        return $this->morphMany(Notification::class, 'notifiable')->latest();
    }

    /**
     * Get the entity's unread notifications.
     *
     * @return MorphMany<Notification, $this>
     */
    public function unreadNotifications(): MorphMany
    {
        return $this->notifications()->whereNull('read_at');
    }

    /**
     * Check if the user's role has permission for a specific file resource action.
     *
     * Every role, Super Admin included, is answered from the page_permissions
     * row for the role and resource. No role short-circuits this method, so
     * revoking a permission genuinely revokes it for everybody and the
     * folder, page grouping, and page matrices stay the single source of truth.
     *
     * @param  FileResources  $resource  The file resource enum (e.g., FileResource::USER, FileResource::INVOICE).
     * @param  FileAction  $action  The action enum being evaluated (defaults to FileAction::READ).
     * @return bool True if permitted, false otherwise.
     */
    public function hasFilePermission(
        FileResources $resource,
        FileAction $action = FileAction::READ
    ): bool {
        if (! $this->role_id) {
            return false;
        }

        $permission = PagePermission::query()
            ->where('role_id', $this->role_id)
            ->whereHas('page', function ($query) use ($resource) {
                $query->where('route_name', $resource->value);
            })
            ->first();

        if (! $permission) {
            return false;
        }

        return match ($action) {
            FileAction::READ => $permission->can_read,
            FileAction::ADD => $permission->can_add,
            FileAction::UPDATE => $permission->can_update,
            FileAction::REMOVE => $permission->can_remove,
            FileAction::APPROVE => $permission->can_approve,
            FileAction::CANCEL => $permission->can_cancel,
        };
    }

    /**
     * Get the options for logging activity using Spatie Activitylog.
     *
     * @return LogOptions Returns configured log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
