<?php

namespace App\Services\Personnel;

use App\Enums\Claim\ClaimStatus;
use App\Enums\Claim\ClaimType;
use App\Enums\DocumentSubType;
use App\Models\Claim\Claim;
use App\Models\System\User;
use App\Traits\GeneratesDocumentNumber;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ClaimService
{
    use GeneratesDocumentNumber;

    /**
     * Get the user's claim dashboard data.
     *
     * @param  string  $userId  The user ID.
     * @param  int  $perPage  The pagination size.
     * @return array{recent: LengthAwarePaginator<int, Claim>, pending_count: int}
     */
    public function getDashboardData(string $userId, int $perPage = 15): array
    {
        $recentRequests = Claim::query()
            ->where('user_id', $userId)
            ->latest('receipt_date')
            ->paginate($perPage);

        $pendingCount = Claim::query()
            ->where('user_id', $userId)
            ->whereIn('status', [ClaimStatus::PENDING->value, ClaimStatus::SUBMITTED->value])
            ->count();

        return [
            'recent' => $recentRequests,
            'pending_count' => $pendingCount,
        ];
    }

    /**
     * Create a new claim.
     *
     * The intranet edition has a single plain claim: no types, no limits and
     * no company/contact coupling. The stored claim_type stays NORMAL because
     * the column is required by the schema.
     *
     * @param  array<string, mixed>  $validated  The validated data.
     * @param  string|null  $attachmentPath  The optional attachment path.
     * @param  bool  $isDraft  Whether to save as draft.
     * @param  User  $claimant  The user creating the claim.
     * @return Claim The created claim.
     */
    public function createClaim(array $validated, ?string $attachmentPath, bool $isDraft, User $claimant): Claim
    {
        $status = $isDraft ? ClaimStatus::DRAFT->value : ClaimStatus::SUBMITTED->value;

        return Claim::query()->create([
            'user_id' => $claimant->id,
            'organization_id' => $claimant->organization_id,
            'reference_number' => $this->generateDocumentNumber(DocumentSubType::CLAIM),
            'claim_type' => ClaimType::NORMAL->value,
            'receipt_date' => $validated['receipt_date'],
            'receipt_no' => $validated['receipt_no'] ?? null,
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'attachment_path' => $attachmentPath,
            'status' => $status,
        ]);
    }

    /**
     * Update an existing claim.
     *
     * @param  Claim  $claim  The claim to update.
     * @param  array<string, mixed>  $validated  The validated data.
     * @param  string|null  $attachmentPath  The optional new attachment path.
     * @param  bool  $isDraft  Whether to save as draft.
     * @param  User  $claimant  The user updating the claim.
     * @return void Returns nothing after updating the claim.
     */
    public function updateClaim(Claim $claim, array $validated, ?string $attachmentPath, bool $isDraft, User $claimant): void
    {
        $status = $isDraft ? ClaimStatus::DRAFT->value : ClaimStatus::SUBMITTED->value;

        $claim->update([
            'organization_id' => $claimant->organization_id,
            'claim_type' => ClaimType::NORMAL->value,
            'receipt_date' => $validated['receipt_date'],
            'receipt_no' => $validated['receipt_no'] ?? null,
            'amount' => $validated['amount'],
            'description' => $validated['description'] ?? null,
            'attachment_path' => $attachmentPath,
            'status' => $status,
        ]);
    }

    /**
     * Delete a claim and its attachment.
     *
     * @param  Claim  $claim  The claim to delete.
     * @return void Returns nothing after deleting the claim.
     */
    public function deleteClaim(Claim $claim): void
    {
        if ($claim->attachment_path && Storage::disk('public')->exists($claim->attachment_path)) {
            Storage::disk('public')->delete($claim->attachment_path);
        }

        $claim->delete();
    }

    /**
     * Handle attachment upload for claim.
     *
     * @param  UploadedFile|null  $file  The uploaded file.
     * @param  string|null  $existingPath  The existing attachment path to replace.
     * @return string|null The new attachment path.
     */
    public function handleAttachment(?UploadedFile $file, ?string $existingPath = null): ?string
    {
        if ($file === null) {
            return $existingPath;
        }

        if ($existingPath && Storage::disk('public')->exists($existingPath)) {
            Storage::disk('public')->delete($existingPath);
        }

        return $file->store('claims', 'public') ?: null;
    }
}
