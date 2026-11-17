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
 * PROPRIETARY AND CONFIDENTIAL
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */

namespace App\Traits;

use App\Enums\DocumentSubType;
use App\Models\System\DocumentSequence;
use Illuminate\Support\Facades\DB;
use LogicException;
use Throwable;

trait GeneratesDocumentNumber
{
    /**
     * Generate a sequential document number for any ledger subtype.
     *
     * @param  DocumentSubType  $subtype  The document type that determines the sequence and prefix.
     * @return string The formatted document number (e.g., LEV10001).
     *
     * @throws Throwable
     */
    public function generateDocumentNumber(DocumentSubType $subtype): string
    {
        return $this->generateSequenceNumber($subtype->value);
    }

    /**
     * Generate the next locked number for one document sequence.
     *
     * @param  string  $type  The document sequence type key configured by DocumentSequenceSeeder.
     * @return string The formatted document number (prefix + sequential number).
     *
     * @throws Throwable
     */
    private function generateSequenceNumber(string $type): string
    {
        return DB::transaction(function () use ($type): string {
            $sequence = DocumentSequence::query()
                ->where('type', $type)
                ->lockForUpdate()
                ->first();

            if ($sequence === null) {
                throw new LogicException(
                    "Document sequence [$type] is not configured. Run the database seeders before issuing documents."
                );
            }

            $rawNextNumber = $sequence->next_number;
            $rawPrefix = $sequence->prefix;

            if ($rawPrefix === '') {
                throw new LogicException("Document sequence [$type] has invalid configuration.");
            }

            $nextNumber = (int) $rawNextNumber;

            DocumentSequence::query()
                ->where('id', $sequence->id)
                ->update([
                    'next_number' => $nextNumber + 1,
                    'updated_at' => now(),
                ]);

            return mb_strtoupper($rawPrefix).$nextNumber;
        });
    }
}
