<?php

namespace Database\Seeders;

use App\Models\System\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LogicException;
use Random\RandomException;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @throws RandomException
     */
    public function run(): void
    {
        $editorUser = User::query()->where('email', 'editor@example.com')->first();
        $standardUser = User::query()->where('email', 'user@example.com')->first();

        if ($editorUser === null || $standardUser === null) {
            throw new LogicException('news seeding requires the editor and standard users.');
        }

        $categories = [
            ['name' => 'Technology', 'description' => 'Software & Tech Updates'],
            ['name' => 'Business', 'description' => 'Economics & Markets'],
        ];

        foreach ($categories as $cat) {
            $existingCat = DB::table('news_categories')->where('name', $cat['name'])->first();
            $catId = $existingCat->id ?? (string) Str::uuid7();

            DB::table('news_categories')->updateOrInsert(
                ['name' => $cat['name']],
                [
                    'id' => $catId,
                    'description' => $cat['description'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            for ($i = 1; $i <= 2; $i++) {
                $title = "{$cat['name']} Article #$i";

                // Check for existing article by title and category instead of slug
                $existingNews = DB::table('news')
                    ->where('title', $title)
                    ->where('news_category_id', $catId)
                    ->first();

                $newsId = $existingNews->id ?? (string) Str::uuid7();

                DB::table('news')->updateOrInsert(
                    ['id' => $newsId],
                    [
                        'title' => $title,
                        'short_description' => 'Summary excerpt for '.$title,
                        'content' => '<p>Main content body for '.$title.'</p>',
                        'featured_image' => 'https://picsum.photos/seed/'.random_int(1, 999).'/800/600',                        'news_category_id' => $catId,
                        'status' => 'published',
                        'views_count' => fake()->numberBetween(50, 500),
                        'published_at' => fake()->dateTimeBetween('-10 days', 'now'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Parent Comment
                $parentCommentText = 'Insightful article! Thanks for sharing.';
                $existingParentComment = DB::table('news_comments')
                    ->where('news_id', $newsId)
                    ->where('comment', $parentCommentText)
                    ->first();

                $commentId = $existingParentComment->id ?? (string) Str::uuid7();

                DB::table('news_comments')->updateOrInsert(
                    ['id' => $commentId],
                    [
                        'news_id' => $newsId,
                        'user_id' => $standardUser->id,
                        'parent_id' => null,
                        'comment' => $parentCommentText,
                        'likes_count' => 1,
                        'unlikes_count' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Comment Like
                $existingLike = DB::table('news_comment_likes')
                    ->where('comment_id', $commentId)
                    ->where('user_id', $editorUser->id)
                    ->first();

                DB::table('news_comment_likes')->updateOrInsert(
                    [
                        'comment_id' => $commentId,
                        'user_id' => $editorUser->id,
                    ],
                    [
                        'id' => $existingLike->id ?? (string) Str::uuid7(),
                        'type' => 'like',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                // Child Reply Comment
                $replyCommentText = 'Glad you found it helpful!';
                $existingReply = DB::table('news_comments')
                    ->where('news_id', $newsId)
                    ->where('parent_id', $commentId)
                    ->first();

                DB::table('news_comments')->updateOrInsert(
                    ['id' => $existingReply->id ?? (string) Str::uuid7()],
                    [
                        'news_id' => $newsId,
                        'user_id' => $editorUser->id,
                        'parent_id' => $commentId,
                        'comment' => $replyCommentText,
                        'likes_count' => 0,
                        'unlikes_count' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
