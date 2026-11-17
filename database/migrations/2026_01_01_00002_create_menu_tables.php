<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('folders', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('icon')->default('fa-folder');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_groupings', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('folder_id')->constrained('folders')->cascadeOnDelete();
            $table->string('name');
            $table->string('icon')->default('fa-layer-group');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('folder_permissions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignUuid('folder_id')->constrained('folders')->cascadeOnDelete();
            $table->boolean('can_read')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['role_id', 'folder_id']);
        });

        Schema::create('page_grouping_permissions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignUuid('page_grouping_id')->constrained('page_groupings')->cascadeOnDelete();
            $table->boolean('can_read')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['role_id', 'page_grouping_id']);
        });

        Schema::create('pages', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('folder_id')->constrained('folders')->cascadeOnDelete();
            $table->foreignUuid('page_grouping_id')->nullable()->constrained('page_groupings')->cascadeOnDelete();
            $table->string('name');
            $table->string('route_name')->nullable();
            $table->string('icon')->default('fa-file');
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('page_permissions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('role_id')->constrained('roles')->cascadeOnDelete();
            $table->foreignUuid('page_id')->constrained('pages')->cascadeOnDelete();
            $table->boolean('can_read')->default(false);
            $table->boolean('can_add')->default(false);
            $table->boolean('can_update')->default(false);
            $table->boolean('can_remove')->default(false);
            $table->boolean('can_approve')->default(false);
            $table->boolean('can_cancel')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['role_id', 'page_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_permissions');
        Schema::dropIfExists('pages');
        Schema::dropIfExists('page_grouping_permissions');
        Schema::dropIfExists('folder_permissions');
        Schema::dropIfExists('page_groupings');
        Schema::dropIfExists('folders');
    }
};
