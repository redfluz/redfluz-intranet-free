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
 *
 * PROPRIETARY AND CONFIDENTIAL
 *
 * All code, concepts, and intellectual property contained in this file are the
 * exclusive property of redfluz. Unauthorized copying, distribution, modification,
 * reverse engineering, or public display of this software via any medium is
 * strictly prohibited without explicit written permission from the copyright owner.
 */

namespace App\Http\Controllers\Personnel;

use App\Constants\FlashMessage;
use App\Enums\FileResources;
use App\Enums\MessageDeletionMode;
use App\Events\MessageDeleted;
use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Personnel\Message\DeleteMessageRequest;
use App\Http\Requests\Personnel\Message\SendMessageRequest;
use App\Models\Personnel\Message;
use App\Models\System\User;
use App\Services\Personnel\MessageService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;

class MessageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  MessageService  $messageService  The message service instance.
     */
    public function __construct(private readonly MessageService $messageService) {}

    /**
     * Display a listing of the resource.
     *
     * @return View Returns the rendered Blade view.
     */
    public function index(): View
    {
        $authId = (string) (auth()->id() ?? '');

        $allUsers = $this->messageService->getAllUsersExceptAuth($authId);
        $recentUsers = $this->messageService->getRecentUsers($authId);

        return view('personnel.messages.index', compact('recentUsers', 'allUsers'));
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user  The user resource instance.
     * @return View Returns the rendered Blade view.
     */
    public function show(User $user): View
    {
        $authId = (string) (auth()->id() ?? '');

        $allUsers = $this->messageService->getAllUsersExceptAuth($authId);
        $this->messageService->markAsRead($user->id, $authId);
        $messages = $this->messageService->getConversationMessages($authId, $user->id);
        $recentUsers = $this->messageService->getRecentUsers($authId);

        return view('personnel.messages.show', compact('user', 'messages', 'recentUsers', 'allUsers'));
    }

    /**
     * Handle the incoming request to create a new resource.
     * Validates the incoming request data and stores a new record in the database.
     *
     * @param  SendMessageRequest  $request  The HTTP request instance containing updated data.
     * @param  User  $user  The user resource instance.
     * @return JsonResponse Returns response JSON with status and message payload.
     */
    public function sendMessage(SendMessageRequest $request, User $user): JsonResponse
    {
        /** @var array{message: ?string, attachment: ?UploadedFile, parent_id: ?string} $validated */
        $validated = $request->validated();

        $message = $this->messageService->sendMessage($validated, (string) (auth()->id() ?? ''), $user->id);
        $message->load('parent.sender');

        broadcast(new MessageSent($message));

        return response()->json([
            'status' => 'success',
            'message' => $message,
        ]);
    }

    /**
     * Update an existing resource by marking messages as read.
     *
     * @param  User  $user  The user resource instance.
     * @return JsonResponse Returns response JSON status.
     */
    public function markAsRead(User $user): JsonResponse
    {
        $this->messageService->markAsRead($user->id, (string) (auth()->id() ?? ''));

        return response()->json(['status' => 'success']);
    }

    /**
     * Hide a message for the current user, or recall it for both users.
     *
     * @param  DeleteMessageRequest  $request  Validates the deletion mode.
     * @param  Message  $message  The message resource instance.
     * @return JsonResponse Returns JSON status.
     */
    public function destroyMessage(DeleteMessageRequest $request, Message $message): JsonResponse
    {
        /** @var MessageDeletionMode $mode */
        $mode = MessageDeletionMode::from($request->validated('mode'));
        $authId = (string) (auth()->id() ?? '');

        $deleted = $mode === MessageDeletionMode::ME
            ? $this->messageService->deleteMessageForMe($message, $authId)
            : $this->messageService->deleteMessageForEveryone($message, $authId);

        if (! $deleted) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        broadcast(new MessageDeleted(
            $message->id,
            $mode->value,
            $authId,
            $message->sender_id,
            $message->receiver_id,
        ));

        return response()->json(['status' => 'success']);
    }

    /**
     * Remove a resource by its object.
     *
     * @param  User  $user  The user resource instance.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function destroyConversation(User $user): RedirectResponse
    {
        // Soft delete: clears the thread for both users; the contact then
        // leaves the recent list because both message directions are scoped.
        $this->messageService->deleteConversation((string) (auth()->id() ?? ''), $user->id);

        return redirect()
            ->back()
            ->with('success', FlashMessage::deleted(FileResources::MESSAGES));
    }

    /**
     * Permanently remove a conversation and the contact from the sidebar.
     *
     * @param  User  $user  The user resource instance.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function destroyContact(User $user): RedirectResponse
    {
        $this->messageService->forceDeleteConversation((string) (auth()->id() ?? ''), $user->id);

        $routeUser = request()->route('user');
        if ($routeUser instanceof User && $routeUser->id === $user->id) {
            return redirect()
                ->route('personnel.messages.index')
                ->with('success', FlashMessage::deleted(FileResources::MESSAGES));
        }

        return redirect()
            ->back()
            ->with('success', FlashMessage::deleted(FileResources::MESSAGES));
    }
}
