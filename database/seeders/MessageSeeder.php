<?php

namespace Database\Seeders;

use App\Models\System\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUserId = User::query()->where('email', 'admin@example.com')->value('id');
        $managerUserId = User::query()->where('email', 'manager@example.com')->value('id');
        $editorUserId = User::query()->where('email', 'editor@example.com')->value('id');
        $standardUserId = User::query()->where('email', 'user@example.com')->value('id');

        $messages = [
            [
                'sender_id' => $adminUserId,
                'receiver_id' => $managerUserId,
                'message' => 'Hi Manager, please verify the new ledger invoices for Acme Corp.',
                'attachment' => null,
                'attachment_type' => null,
                'original_name' => null,
                'is_read' => true,
                'created_at' => now()->subHours(5),
            ],
            [
                'sender_id' => $managerUserId,
                'receiver_id' => $adminUserId,
                'message' => 'Sure Admin! I checked them and marked invoice #INV-2026-001 as paid.',
                'attachment' => null,
                'attachment_type' => null,
                'original_name' => null,
                'is_read' => true,
                'created_at' => now()->subHours(4),
            ],
            [
                'sender_id' => $adminUserId,
                'receiver_id' => $managerUserId,
                'message' => 'Great work. Can you also broadcast a system maintenance announcement?',
                'attachment' => null,
                'attachment_type' => null,
                'original_name' => null,
                'is_read' => false,
                'created_at' => now()->subMinutes(30),
            ],
            [
                'sender_id' => $editorUserId,
                'receiver_id' => $standardUserId,
                'message' => 'Hello! Thanks for your comment on the latest Technology article.',
                'attachment' => null,
                'attachment_type' => null,
                'original_name' => null,
                'is_read' => true,
                'created_at' => now()->subDays(),
            ],
            [
                'sender_id' => $standardUserId,
                'receiver_id' => $editorUserId,
                'message' => 'You are welcome! Will there be a follow-up post on AI developments soon?',
                'attachment' => null,
                'attachment_type' => null,
                'original_name' => null,
                'is_read' => false,
                'created_at' => now()->subMinutes(15),
            ],
            [
                'sender_id' => $adminUserId,
                'receiver_id' => $standardUserId,
                'message' => 'Welcome to TADA Platform! Feel free to reach out if you need assistance.',
                'attachment' => null,
                'attachment_type' => null,
                'original_name' => null,
                'is_read' => false,
                'created_at' => now()->subMinutes(45),
            ],
        ];

        foreach ($messages as $msg) {
            if ($msg['sender_id'] && $msg['receiver_id']) {
                $existing = DB::table('messages')
                    ->where('sender_id', $msg['sender_id'])
                    ->where('receiver_id', $msg['receiver_id'])
                    ->where('created_at', $msg['created_at'])
                    ->first();

                DB::table('messages')->updateOrInsert(
                    [
                        'sender_id' => $msg['sender_id'],
                        'receiver_id' => $msg['receiver_id'],
                        'created_at' => $msg['created_at'],
                    ],
                    [
                        'id' => $existing->id ?? (string) Str::uuid7(),
                        'message' => $msg['message'],
                        'attachment' => $msg['attachment'],
                        'attachment_type' => $msg['attachment_type'],
                        'original_name' => $msg['original_name'],
                        'is_read' => $msg['is_read'],
                        'updated_at' => $msg['created_at'],
                    ]
                );
            }
        }
    }
}
