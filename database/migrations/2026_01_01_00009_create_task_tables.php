<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_templates', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('title');
            $table->enum('default_priority', ['low', 'medium', 'high'])->default('medium');
            $table->unsignedSmallInteger('default_due_days')->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('is_active');
        });

        Schema::create('tasks', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('reference_number')->nullable()->index();
            $table->foreignUuid('task_template_id')->nullable()->constrained('task_templates')->nullOnDelete();
            $table->string('source_type')->nullable();
            $table->uuid('source_id')->nullable();
            $table->string('title');
            $table->foreignUuid('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('stage', [
                'introduction',
                'follow_up_1',
                'follow_up_2',
                'proceed',
                'success',
                'failure',
            ])->default('introduction');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->unsignedInteger('position')->default(0);
            $table->dateTime('due_date')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['stage', 'position']);
            $table->index(['source_type', 'source_id']);
        });

        Schema::create('task_attachments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('mime_type');
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('task_workflows', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('is_active');
        });

        Schema::create('task_workflow_items', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('task_workflow_id')->constrained('task_workflows')->cascadeOnDelete();
            $table->string('title');
            $table->enum('default_priority', ['low', 'medium', 'high'])->default('medium');
            $table->unsignedSmallInteger('default_due_days')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['task_workflow_id', 'position'], 'task_workflow_items_position_index');
        });

        Schema::create('task_notes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('task_id')->constrained('tasks')->cascadeOnDelete();
            $table->foreignUuid('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['progress', 'parts_required', 'completion', 'failure']);
            $table->text('note');
            $table->timestamps();
            $table->index(['task_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_notes');
        Schema::dropIfExists('task_workflow_items');
        Schema::dropIfExists('task_workflows');
        Schema::dropIfExists('task_attachments');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('task_templates');
    }
};
