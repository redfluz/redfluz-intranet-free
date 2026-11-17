<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('timezones', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('zone')->unique();
            $table->string('utc_offset', 10)->nullable();
            $table->string('abbreviation', 10)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('currencies', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('code', 3)->unique();
            $table->string('name');
            $table->string('symbol', 5)->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('timezone_uuid')->nullable();
            $table->uuid('currency_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_mobile', 50)->nullable();
            $table->string('company_fax')->nullable();
            $table->string('company_website')->nullable();
            $table->string('company_registration_no')->nullable();
            $table->string('company_tax_no')->nullable();
            $table->string('logo_path')->nullable();
            $table->text('address')->nullable();
            $table->string('company_postcode', 20)->nullable();
            $table->string('company_city')->nullable();
            $table->string('company_state')->nullable();
            $table->string('company_country')->nullable();
            $table->string('short_date_format', 30)->default('d/m/Y');
            $table->string('long_date_format', 30)->default('d/m/Y H:i:s');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('document_sequences', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('type')->unique();
            $table->string('prefix');
            $table->unsignedBigInteger('next_number')->default(10001);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('activity_log', function (Blueprint $table): void {
            $table->id();
            $table->string('log_name')->nullable()->index();
            $table->text('description');
            $table->string('subject_type')->nullable();
            $table->string('subject_id', 36)->nullable();
            $table->index(['subject_type', 'subject_id']);
            $table->string('event')->nullable();
            $table->string('causer_type')->nullable();
            $table->string('causer_id', 36)->nullable();
            $table->index(['causer_type', 'causer_id']);
            $table->json('attribute_changes')->nullable();
            $table->json('properties')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_log');
        Schema::dropIfExists('document_sequences');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('currencies');
        Schema::dropIfExists('timezones');
    }
};
