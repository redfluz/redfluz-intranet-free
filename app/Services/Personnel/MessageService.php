<?php

namespace App\Services\Personnel;

use App\Models\Personnel\Message;
use App\Models\System\User;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class MessageService
{
    /**
     * @param  string  $authId  The authenticated user ID.
     * @return EloquentCollection<int, User>
     */
    public function getAllUsersExceptAuth(string $authId): EloquentCollection
    {
        return User::query()
            ->where('id', '!=', $authId)
            ->orderBy('name')
            ->get();
    }

    /**
     * @param  string  $authId  The authenticated user ID.
     * @return Collection<int, array{user: User, unread_count: int, last_message: ?Message}>
     */
    public function getRecentUsers(string $authId): Collection
    {
        // Both directions now go through Eloquent so the sidebar honours the
        // global soft delete and the viewer's per-message hides consistently.
        $incomingUserIds = Message::query()
            ->where('receiver_id', $authId)
            ->whereDoesntHave('deletions', $this->hiddenBy($authId))
            ->pluck('sender_id');

        $outgoingUserIds = Message::query()
            ->where('sender_id', $authId)
            ->whereDoesntHave('deletions', $this->hiddenBy($authId))
            ->pluck('receiver_id');

        $recentUserIds = $incomingUserIds->concat($outgoingUserIds)->unique()->values();

        $users = User::query()->whereIn('id', $recentUserIds)->orderBy('name')->get();

        return $users->map(function (User $user) use ($authId) {
            $unreadCount = Message::query()
                ->where('sender_id', $user->id)
                ->where('receiver_id', $authId)
                ->where('is_read', false)
                ->whereDoesntHave('deletions', $this->hiddenBy($authId))
                ->count();

            $lastMessage = Message::query()
                ->where(function ($q) use ($authId, $user): void {
                    $q->where(function ($pair) use ($authId, $user): void {
                        $pair->where('sender_id', $authId)
                            ->where('receiver_id', $user->id);
                    })->orWhere(function ($pair) use ($authId, $user): void {
                        $pair->where('sender_id', $user->id)
                            ->where('receiver_id', $authId);
                    });
                })
                ->whereDoesntHave('deletions', $this->hiddenBy($authId))
                ->latest()
                ->first();

            return [
                'user' => $user,
                'unread_count' => $unreadCount,
                'last_message' => $lastMessage,
            ];
        });
    }

    /**
     * @param  string  $authId  The authenticated user ID.
     * @param  string  $otherUserId  The other user ID in the conversation.
     * @return EloquentCollection<int, Message>
     */
    public function getConversationMessages(string $authId, string $otherUserId): EloquentCollection
    {
        return Message::query()
            ->with(['parent.sender'])
            ->where(function ($q) use ($authId, $otherUserId): void {
                $q->where(function ($pair) use ($authId, $otherUserId): void {
                    $pair->where('sender_id', $authId)
                        ->where('receiver_id', $otherUserId);
                })->orWhere(function ($pair) use ($authId, $otherUserId): void {
                    $pair->where('sender_id', $otherUserId)
                        ->where('receiver_id', $authId);
                });
            })
            ->whereDoesntHave('deletions', $this->hiddenBy($authId))
            ->oldest()
            ->get();
    }

    public function markAsRead(string $senderId, string $receiverId): void
    {
        Message::query()
            ->where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('is_read', false)
            ->whereDoesntHave('deletions', $this->hiddenBy($receiverId))
            ->update(['is_read' => true]);
    }

    /**
     * @param  array{message: ?string, attachment: ?UploadedFile, parent_id: ?string}  $data  The message payload.
     * @param  string  $senderId  The sender user ID.
     * @param  string  $receiverId  The receiver user ID.
     * @return Message The created message.
     */
    public function sendMessage(array $data, string $senderId, string $receiverId): Message
    {
        $attachmentPath = null;
        $attachmentType = null;
        $originalName = null;

        if (isset($data['attachment'])) {
            $file = $data['attachment'];
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());

            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
            $attachmentType = in_array($extension, $imageExtensions) ? 'image' : 'document';

            $attachmentPath = $file->store('attachments', 'public');
        }

        return Message::query()->create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $data['message'],
            'attachment' => $attachmentPath,
            'attachment_type' => $attachmentType,
            'original_name' => $originalName,
            'parent_id' => $data['parent_id'],
        ]);
    }

    /**
     * Hide a message from one participant only. The row and its attachment stay
     * intact for the other participant.
     */
    public function deleteMessageForMe(Message $message, string $authId): bool
    {
        if ($message->sender_id !== $authId && $message->receiver_id !== $authId) {
            return false;
        }

        $message->hideFor($authId);

        return true;
    }

    /**
     * Recall a message for both participants. Sender only, because removing
     * another user's message from their view is not allowed.
     */
    public function deleteMessageForEveryone(Message $message, string $authId): bool
    {
        if ($message->sender_id !== $authId) {
            return false;
        }

        // No per-user markers are meaningful once the row is gone globally.
        $message->deletions()->delete();

        if ($message->attachment && Storage::disk('public')->exists($message->attachment)) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return true;
    }

    /**
     * Filter for "the current viewer has not hidden this message".
     *
     * @return Closure(Builder): void
     */
    private function hiddenBy(string $userId): Closure
    {
        return static function ($query) use ($userId): void {
            $query->where('user_id', $userId);
        };
    }

    /**
     * @param  string  $authId  The authenticated user ID.
     * @param  string  $otherUserId  The other user ID in the conversation.
     * @return Collection<int, non-empty-string>
     */
    public function deleteConversation(string $authId, string $otherUserId): Collection
    {
        $query = Message::query()
            ->where(function ($q) use ($authId, $otherUserId) {
                $q->where('sender_id', $authId)
                    ->where('receiver_id', $otherUserId);
            })
            ->orWhere(function ($q) use ($authId, $otherUserId) {
                $q->where('sender_id', $otherUserId)
                    ->where('receiver_id', $authId);
            });

        $attachments = (clone $query)
            ->whereNotNull('attachment')
            ->where('attachment', '!=', '')
            ->pluck('attachment')
            ->filter(static fn (mixed $path): bool => is_string($path) && $path !== '')
            ->values();

        foreach ($attachments as $filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $query->delete();

        return $attachments;
    }

    /**
     * Permanently remove a conversation so the contact also leaves the recent list.
     *
     * @param  string  $authId  The authenticated user ID.
     * @param  string  $otherUserId  The other user ID in the conversation.
     * @return Collection<int, non-empty-string>
     */
    public function forceDeleteConversation(string $authId, string $otherUserId): Collection
    {
        $query = Message::query()
            ->withTrashed()
            ->where(function ($q) use ($authId, $otherUserId): void {
                $q->where('sender_id', $authId)
                    ->where('receiver_id', $otherUserId);
            })
            ->orWhere(function ($q) use ($authId, $otherUserId): void {
                $q->where('sender_id', $otherUserId)
                    ->where('receiver_id', $authId);
            });

        $attachments = (clone $query)
            ->whereNotNull('attachment')
            ->where('attachment', '!=', '')
            ->pluck('attachment')
            ->filter(static fn (mixed $path): bool => is_string($path) && $path !== '')
            ->values();

        foreach ($attachments as $filePath) {
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }

        $query->forceDelete();

        return $attachments;
    }
}
