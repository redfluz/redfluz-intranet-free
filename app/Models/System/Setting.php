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

use App\Models\Finance\Currency;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Models\Concerns\HasActivity;
use Spatie\Activitylog\Support\LogOptions;
use Throwable;

/**
 * Singleton company profile row.
 *
 * Holds only the company identity printed on documents, invoices and PDFs, plus
 * the logo shown in the sidebar, top bar and login screen. There is exactly one
 * row; the settings controller creates it on first visit.
 *
 * @mixin Builder<Setting>
 *
 * @property string $id
 * @property string|null $timezone_uuid
 * @property string|null $currency_id
 * @property string|null $company_name
 * @property string|null $company_email
 * @property string|null $company_phone
 * @property string|null $company_fax
 * @property string|null $company_website
 * @property string|null $company_registration_no
 * @property string|null $company_tax_no
 * @property string|null $tax_id_type_code
 * @property string|null $employer_category
 * @property string|null $employer_status
 * @property string|null $lhdn_state
 * @property string|null $logo_path
 * @property string|null $address
 * @property string|null $company_postcode
 * @property string|null $company_city
 * @property string|null $company_state
 * @property string|null $company_country
 * @property string|null $company_mobile
 * @property string|null $cp8d_submission_method
 * @property int|null $rma_warranty_months
 * @property string|null $rma_role_id
 * @property string|null $tax_agent_name
 * @property string|null $tax_agent_approval_no
 * @property string|null $tax_agent_firm_name
 * @property string|null $tax_agent_firm_address
 * @property string|null $tax_agent_firm_postcode
 * @property string|null $tax_agent_firm_city
 * @property string|null $tax_agent_firm_state
 * @property string|null $tax_agent_firm_tin
 * @property string|null $tax_agent_phone
 * @property string|null $tax_agent_email
 * @property string|null $tax_agent_signature_text
 * @property CarbonInterface|null $tax_agent_signature_date
 * @property string|null $tax_declarant_name
 * @property string|null $tax_declarant_id_passport
 * @property string|null $tax_declarant_position
 * @property string|null $tax_declarant_signature_text
 * @property string|null $mail_mailer
 * @property string|null $mail_host
 * @property int|null $mail_port
 * @property string|null $mail_username
 * @property string|null $mail_password
 * @property string|null $mail_encryption
 * @property string|null $mail_from_address
 * @property string|null $mail_from_name
 * @property string $short_date_format
 * @property string $long_date_format
 * @property CarbonInterface|null $created_at
 * @property CarbonInterface|null $updated_at
 * @property CarbonInterface|null $deleted_at
 * @property-read Timezone|null $timezone
 * @property-read Currency|null $currency
 * @property-read Role|null $rmaRole
 * @property-read Collection<int, Activity> $activities
 * @property-read Collection<int, Activity> $activitiesAsCauser
 * @property-read Collection<int, Activity> $activitiesAsSubject
 */
#[Table('settings', key: 'id')]
#[Fillable([
    'timezone_uuid',
    'currency_id',
    'company_name',
    'company_email',
    'company_phone',
    'company_fax',
    'company_website',
    'company_registration_no',
    'company_tax_no',
    'tax_id_type_code',
    'employer_category',
    'employer_status',
    'lhdn_state',
    'logo_path',
    'address',
    'company_postcode',
    'company_city',
    'company_state',
    'company_country',
    'company_mobile',
    'cp8d_submission_method',
    'tax_agent_name',
    'tax_agent_approval_no',
    'tax_agent_firm_name',
    'tax_agent_firm_address',
    'tax_agent_firm_postcode',
    'tax_agent_firm_city',
    'tax_agent_firm_state',
    'tax_agent_firm_tin',
    'tax_agent_phone',
    'tax_agent_email',
    'tax_agent_signature_text',
    'tax_agent_signature_date',
    'tax_declarant_name',
    'tax_declarant_id_passport',
    'tax_declarant_position',
    'tax_declarant_signature_text',
    'rma_warranty_months',
    'rma_role_id',
    'mail_mailer',
    'mail_host',
    'mail_port',
    'mail_username',
    'mail_password',
    'mail_encryption',
    'mail_from_address',
    'mail_from_name',
    'short_date_format',
    'long_date_format',
])]
class Setting extends Model
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
            'timezone_uuid' => 'string',
            'currency_id' => 'string',
            'company_name' => 'string',
            'company_email' => 'string',
            'company_phone' => 'string',
            'company_fax' => 'string',
            'company_website' => 'string',
            'company_registration_no' => 'string',
            'company_tax_no' => 'string',
            'tax_id_type_code' => 'string',
            'employer_category' => 'string',
            'employer_status' => 'string',
            'lhdn_state' => 'string',
            'logo_path' => 'string',
            'address' => 'string',
            'company_postcode' => 'string',
            'company_city' => 'string',
            'company_state' => 'string',
            'company_country' => 'string',
            'company_mobile' => 'string',
            'cp8d_submission_method' => 'string',
            'tax_agent_name' => 'string',
            'tax_agent_approval_no' => 'string',
            'tax_agent_firm_name' => 'string',
            'tax_agent_firm_address' => 'string',
            'tax_agent_firm_postcode' => 'string',
            'tax_agent_firm_city' => 'string',
            'tax_agent_firm_state' => 'string',
            'tax_agent_firm_tin' => 'string',
            'tax_agent_phone' => 'string',
            'tax_agent_email' => 'string',
            'tax_agent_signature_text' => 'string',
            'tax_agent_signature_date' => 'date',
            'tax_declarant_name' => 'string',
            'tax_declarant_id_passport' => 'string',
            'tax_declarant_position' => 'string',
            'tax_declarant_signature_text' => 'string',
            'rma_warranty_months' => 'integer',
            'rma_role_id' => 'string',
            'mail_mailer' => 'string',
            'mail_host' => 'string',
            'mail_port' => 'integer',
            'mail_username' => 'string',
            'mail_password' => 'encrypted',
            'mail_encryption' => 'string',
            'mail_from_address' => 'string',
            'mail_from_name' => 'string',
            'short_date_format' => 'string',
            'long_date_format' => 'string',
        ];
    }

    /**
     * Drop the cached settings values whenever the singleton row is written.
     *
     * The currency symbol and the date formats are read on almost every page, so they are cached,
     * but a saved setting must not keep serving the previous value until the cache expires.
     */
    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget('system_currency_symbol');
            Cache::forget('system_date_format_short_date_format');
            Cache::forget('system_date_format_long_date_format');
        });
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * The timezone that determines which regional salary features are active.
     *
     * @return BelongsTo<Timezone, $this>
     */
    public function timezone(): BelongsTo
    {
        return $this->belongsTo(Timezone::class, 'timezone_uuid');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * The default currency for the system.
     *
     * @return BelongsTo<Currency, $this>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Define an inverse one-to-one or many relationship.
     * Role whose users may be assigned RMA inspection and repair work.
     *
     * @return BelongsTo<Role, $this>
     */
    public function rmaRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'rma_role_id');
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

    /**
     * Get the formatted date pattern from settings with caching.
     *
     * @param  string  $type  The date format column ('short_date_format' or 'long_date_format').
     * @return string The cached date format string.
     */
    public static function getDefaultDate(string $type = 'short_date_format'): string
    {
        $default = $type === 'long_date_format' ? 'd M Y, h:i A' : 'd M Y';

        return Cache::remember('system_date_format_'.$type, now()->addHours(24), function () use ($type, $default): string {
            $setting = static::first();

            if ($setting === null) {
                return $default;
            }

            $value = $setting->getAttribute($type);

            return is_string($value) && $value !== '' ? $value : $default;
        });
    }

    /**
     * Get the system currency symbol with caching.
     *
     * A symbol that cannot be resolved is returned but never cached, because a settings row read
     * before its currency exists would otherwise hide the symbol from every money column until the
     * cache expires.
     *
     * @return string The currency symbol (e.g. 'RM', '$') or empty string if not configured.
     */
    public static function getCurrencySymbol(): string
    {
        try {
            $cached = Cache::get('system_currency_symbol');

            if (is_string($cached) && $cached !== '') {
                return $cached;
            }

            $symbol = static::first()?->currency?->symbol;

            if (! is_string($symbol) || $symbol === '') {
                return '';
            }

            Cache::put('system_currency_symbol', $symbol, now()->addHours(24));

            return $symbol;
        } catch (Throwable) {
            return '';
        }
    }
}
