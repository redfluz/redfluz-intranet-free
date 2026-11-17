<?php

namespace Database\Seeders;

use App\Enums\DocumentSubType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/** Seeds the configured document-number types and their prefixes. */
class DocumentSequenceSeeder extends Seeder
{
    private const int FIRST_NUMBER = 10001;

    /**
     * Configure every document sequence used by the application.
     */
    public function run(): void
    {
        foreach (DocumentSubType::cases() as $subtype) {
            $type = $subtype->value;
            $prefix = $subtype->prefix();

            $existing = DB::table('document_sequences')
                ->where('type', $type)
                ->first(['id', 'created_at', 'next_number']);

            $existingNextNumber = $existing?->next_number;
            $nextNumber = is_numeric($existingNextNumber)
                ? max(self::FIRST_NUMBER, (int) $existingNextNumber)
                : self::FIRST_NUMBER;

            DB::table('document_sequences')->updateOrInsert(
                ['type' => $type],
                [
                    'id' => $existing === null ? (string) Str::uuid7() : $existing->id,
                    'prefix' => $prefix,
                    'next_number' => $nextNumber,
                    'created_at' => $existing === null ? now() : $existing->created_at,
                    'updated_at' => now(),
                ],
            );
        }
    }
}
