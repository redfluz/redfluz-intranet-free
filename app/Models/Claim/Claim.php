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

namespace App\Models\Claim;

use App\Enums\Claim\ClaimStatus;
use App\Enums\Claim\ClaimType;
use App\Models\Management\Organization;
use App\Models\System\User;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @mixin Builder<Claim>
 *
 * @property string $id
 * @property string|null $reference_number
 * @property string $user_id
 * @property string|null $organization_id
 * @property string|null $approved_by
 * @property string|null $created_by
 * @property string|null $company_id
 * @property string|null $product_id
 * @property string|null $contact_id
 * @property ClaimType $claim_type
 * @property CarbonInterface $receipt_date
 * @property string|null $receipt_no
 * @property string $amount
 * @property string|null $project
 * @property string|null $vehicle
 * @property string|null $rate
 * @property string|null $miles
 * @property string|null $claimant
 * @property string|null $appointed
 * @property string|null $clinic
 * @property string|null $customer_name
 * @property string|null $customer_phone
 * @property string|null $customer_email
 * @property string|null $description
 * @property string|null $attachment_path
 * @property ClaimStatus $status
 * @property CarbonInterface|null $approved_at
 * @property string|null $rejection_reason
 * @property string|null $ledger_id
 * @property bool $payment_posted
 * @property CarbonInterface|null $deleted_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @noinspection PhpUnused
 *
 * @property-read User|null $user
 * @property-read Organization|null $organization
 * @property-read User|null $approver
 * @property-read Collection<int, Activity> $activities
 * @property-read Collection<int, Activity> $activitiesAsCauser
 * @property-read Collection<int, Activity> $activitiesAsSubject
 */
#[Table('claims', key: 'id')]
#[Fillable([
    'reference_number',
    'user_id',
    'organization_id',
    'approved_by',
    'created_by',
    'claim_type',
    'receipt_date',
    'receipt_no',
    'amount',
    'project',
    'vehicle',
    'rate',
    'miles',
    'claimant',
    'appointed',
    'clinic',
    'customer_name',
    'customer_phone',
    'customer_email',
    'description',
    'attachment_path',
    'status',
    'approved_at',
    'rejection_reason',
    'ledger_id',
])]
class Claim extends Model
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
            'claim_type' => ClaimType::class,
            'receipt_date' => 'date',
            'amount' => 'decimal:2',
            'rate' => 'decimal:2',
            'miles' => 'decimal:2',
            'status' => ClaimStatus::class,
            'approved_at' => 'datetime',
            'payment_posted' => 'boolean',
        ];
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user who submitted the claim.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the organization associated with the claim.
     *
     * @return BelongsTo<Organization, $this>
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Get the user who approved the claim.
     *
     * @return BelongsTo<User, $this>
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
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
