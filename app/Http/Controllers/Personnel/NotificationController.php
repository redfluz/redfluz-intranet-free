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

use App\Http\Controllers\Controller;
use App\Models\System\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     * Display a listing of user-notifications for the authenticated user.
     *
     * @param  Request  $request  The HTTP request instance.
     * @return View Returns the rendered Blade view.
     */
    public function index(Request $request): View
    {

        $search = $request->input('search');

        /** @var User $user */
        $user = auth()->user();

        $query = $user->notifications();

        if (is_string($search) && $search !== '') {
            $query->where('data', 'like', "%$search%");
        }

        $notifications = $query->paginate(10)->withQueryString();

        return view('personnel.notifications.index', compact('notifications', 'search'));
    }

    /**
     * Display the specified resource.
     * Display the specified notification and mark it as read.
     *
     * @param  string  $id  The notification ID.
     * @return View Returns the rendered Blade view.
     */
    public function show(string $id): View
    {
        /** @var User $user */
        $user = auth()->user();

        /** @var DatabaseNotification $notification */
        $notification = $user->notifications()->findOrFail($id);

        if ($notification->unread()) {
            $notification->markAsRead();
        }

        return view('personnel.notifications.show', compact('notification'));
    }

    /**
     * Mark all unread user-notifications as read for the authenticated user.
     *
     * @return RedirectResponse Redirects back to the index page.
     */
    public function markAllAsRead(): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $user->unreadNotifications->each(static function (DatabaseNotification $notification): void {
            $notification->markAsRead();
        });

        return redirect()
            ->route('personnel.notifications.index')
            ->with('success', __('notifications.message.marked_all_read'));
    }

    /**
     * Remove a resource by its object.
     * Remove the specified notification from storage.
     *
     * @param  string  $id  The notification ID.
     * @return RedirectResponse Redirects back to the index page.
     */
    public function destroy(string $id): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()
            ->route('personnel.notifications.index')
            ->with('success', __('notifications.message.deleted'));
    }
}
