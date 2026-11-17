<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_categories', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('news', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->foreignUuid('news_category_id')->constrained('news_categories')->cascadeOnDelete();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('news_comments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('news_id')->constrained('news')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('parent_id')->nullable()->constrained('news_comments')->cascadeOnDelete();
            $table->text('comment');
            $table->unsignedInteger('likes_count')->default(0);
            $table->unsignedInteger('unlikes_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('news_comment_likes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('comment_id')->constrained('news_comments')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('type', ['like', 'unlike']);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['comment_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_comment_likes');
        Schema::dropIfExists('news_comments');
        Schema::dropIfExists('news');
        Schema::dropIfExists('news_categories');
    }
};
