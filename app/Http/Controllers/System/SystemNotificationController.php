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

namespace App\Http\Controllers\System;

use App\Constants\FlashMessage;
use App\Enums\FileAction;
use App\Enums\FileResources;
use App\Http\Controllers\Controller;
use App\Http\Requests\System\SystemNotification\StoreSystemNotificationRequest;
use App\Models\Management\Department;
use App\Models\System\Notification as SystemNotification;
use App\Models\System\Role;
use App\Models\System\User;
use App\Notifications\AdminBroadcastNotification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SystemNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * Display a listing of system broadcast notifications.
     *
     * @param  Request  $request  The HTTP request instance.
     * @return View Returns the rendered Blade view.
     */
    public function index(Request $request): View
    {
        $this->allows(FileResources::SYSTEM_NOTIFICATIONS);

        $search = $request->input('search');

        $query = SystemNotification::query();

        if (is_string($search) && $search !== '') {
            $query->where(function ($q) use ($search) {
                // Search in JSON 'data' column or 'type'
                $q->where('data', 'like', "%$search%")
                    ->orWhere('type', 'like', "%$search%");
            });
        }

        // ✅ Assign the result of paginate back to $notifications
        $notifications = $query->latest()->paginate(self::PER_PAGE_GRID_NORMAL);

        return view('system.notifications.index', compact('notifications', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     * Show form to send custom broadcast notification.
     *
     * @return View Returns the rendered Blade view.
     */
    public function create(): View
    {
        $this->allows(FileResources::SYSTEM_NOTIFICATIONS, FileAction::ADD);

        /** @var Collection<int, User> $users */
        $users = User::query()
            ->with(['department', 'role'])
            ->orderBy('name', 'asc')
            ->get(['id', 'name', 'email', 'department_id', 'role_id']);

        $departments = Department::query()
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        $roles = Role::query()
            ->orderBy('name', 'asc')
            ->get(['id', 'name']);

        return view('system.notifications.create', compact('users', 'departments', 'roles'));
    }

    /**
     * Handle the incoming request to create a new resource.
     * Store and send custom notification to selected users.
     *
     * @param  StoreSystemNotificationRequest  $request  The HTTP request instance.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function store(StoreSystemNotificationRequest $request): RedirectResponse
    {
        /** @var array<string, mixed> $validated */
        $validated = $request->validated();

        /** @var array<int, string> $userIds */
        $userIds = is_array($validated['users']) ? array_values(array_filter($validated['users'], is_string(...))) : [];
        $title = is_string($validated['title']) ? $validated['title'] : '';
        $message = is_string($validated['message']) ? $validated['message'] : '';

        /** @var Collection<int, User> $recipients */
        $recipients = User::query()
            ->whereIn('id', $userIds)
            ->get();

        foreach ($recipients as $recipient) {
            $recipient->notify(new AdminBroadcastNotification(
                $title,
                $message,
                $recipient->id
            ));
        }

        return redirect()->route('system.notifications.index')
            ->with('success', __('notifications.message.sent', ['count' => $recipients->count()]));
    }

    /**
     * Display the specified resource.
     * Display full details of a specific broadcast notification.
     *
     * @param  string  $id  The id value.
     * @return View Returns the rendered Blade view.
     */
    public function show(string $id): View
    {
        $this->allows(FileResources::SYSTEM_NOTIFICATIONS);

        $notification = SystemNotification::query()
            ->findOrFail($id);

        $recipient = User::query()
            ->find($notification->notifiable_id);

        return view('system.notifications.show', compact('notification', 'recipient'));
    }

    /**
     * Remove a resource by its object.
     * Remove a sent notification from the database.
     *
     * @param  string  $id  The id value.
     * @return RedirectResponse Redirects to the specified route.
     */
    public function destroy(string $id): RedirectResponse
    {
        $this->allows(FileResources::SYSTEM_NOTIFICATIONS, FileAction::REMOVE);

        $notification = SystemNotification::query()
            ->findOrFail($id);

        $notification->delete();

        return redirect()->route('system.notifications.index')
            ->with('success', FlashMessage::deleted(FileResources::SYSTEM_NOTIFICATIONS));
    }
}
