<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var list<string> */
    private array $leaveTypes = [
        'annual', 'sick', 'casual', 'hospitalisation',
        'maternity', 'paternity', 'compassionate', 'marriage', 'unpaid',
    ];

    public function up(): void
    {
        Schema::create('leaves', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('reference_number')->nullable()->unique();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', $this->leaveTypes)->default('annual');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('total_days')->default(1);
            $table->text('reason')->nullable();
            $table->enum('status', ['draft', 'submitted', 'pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->string('attachment_path')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['user_id', 'status']);
            $table->index(['type', 'status']);
        });

        Schema::create('holidays', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->date('date');
            $table->enum('type', ['public', 'company', 'state'])->default('public');
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leave_settings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            // NULL user_id is the global entitlement; a value is a per-user override.
            $table->enum('type', $this->leaveTypes);
            $table->foreignUuid('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('default_days');
            $table->foreignUuid('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['type', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_settings');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('leaves');
    }
};
