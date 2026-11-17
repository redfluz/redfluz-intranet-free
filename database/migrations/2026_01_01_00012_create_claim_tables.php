<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('claims', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('reference_number')->nullable()->unique();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('claim_type');
            $table->date('receipt_date');
            $table->string('receipt_no')->nullable();
            $table->decimal('amount', 15)->default(0.00);
            $table->string('project')->nullable();
            $table->string('vehicle')->nullable();
            $table->decimal('rate')->nullable();
            $table->decimal('miles')->nullable();
            $table->string('claimant')->nullable();
            $table->string('appointed')->nullable();
            $table->string('clinic')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_email')->nullable();
            $table->text('description')->nullable();
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->uuid('ledger_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('claim_settings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('chart_id')->nullable();
            $table->string('claim_type');
            $table->timestamps();
            $table->softDeletes();
            $table->index('claim_type');
            $table->index('chart_id');
        });

        Schema::create('claim_setting_limits', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('claim_setting_id')->constrained('claim_settings')->cascadeOnDelete();
            $table->foreignUuid('organization_id')->constrained('organizations')->restrictOnDelete();
            $table->decimal('max_claim_amount', 15, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['claim_setting_id', 'organization_id'], 'claim_setting_organization_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('claim_setting_limits');
        Schema::dropIfExists('claim_settings');
        Schema::dropIfExists('claims');
    }
};
