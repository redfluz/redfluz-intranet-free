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

use App\Http\Controllers\Personnel\ClaimController;
use App\Http\Controllers\Personnel\DashboardPersonnelController;
use App\Http\Controllers\Personnel\LeaveController;
use App\Http\Controllers\Personnel\MessageController;
use App\Http\Controllers\Personnel\NotificationController;
use App\Http\Controllers\Personnel\ProfileController;
use App\Http\Controllers\Personnel\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', [DashboardPersonnelController::class, 'index'])->name('personnel.dashboard.index');

Route::get('profile/edit', [ProfileController::class, 'edit'])->name('personnel.profile.edit');
Route::put('profile', [ProfileController::class, 'update'])->name('personnel.profile.update');

Route::get('messages', [MessageController::class, 'index'])->name('personnel.messages.index');
Route::get('messages/{user:name}', [MessageController::class, 'show'])->name('personnel.messages.show');
Route::post('messages/{user:name}', [MessageController::class, 'sendMessage'])->name('personnel.messages.send');
Route::patch('messages/{user}/read', [MessageController::class, 'markAsRead'])->name('personnel.messages.read');
Route::delete('messages/message/{message}', [MessageController::class, 'destroyMessage'])->name('personnel.messages.destroy-message');
Route::delete('messages/{user}', [MessageController::class, 'destroyConversation'])->name('personnel.messages.destroy-conversation');
Route::delete('messages/{user}/contact', [MessageController::class, 'destroyContact'])->name('personnel.messages.destroy-contact');

Route::post('notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('personnel.notifications.mark-all-as-read');
Route::get('notifications', [NotificationController::class, 'index'])->name('personnel.notifications.index');
Route::get('notifications/{id}', [NotificationController::class, 'show'])->name('personnel.notifications.show');
Route::delete('notifications/{id}', [NotificationController::class, 'destroy'])->name('personnel.notifications.destroy');

Route::get('claims', [ClaimController::class, 'index'])->name('personnel.claims.index');
Route::get('claims/create', [ClaimController::class, 'create'])->name('personnel.claims.create');
Route::post('claims', [ClaimController::class, 'store'])->name('personnel.claims.store');
Route::get('claims/{claim}/edit', [ClaimController::class, 'edit'])->name('personnel.claims.edit');
Route::put('claims/{claim}', [ClaimController::class, 'update'])->name('personnel.claims.update');
Route::delete('claims/{claim}', [ClaimController::class, 'destroy'])->name('personnel.claims.destroy');

Route::get('leaves/calendar', [LeaveController::class, 'calendar'])->name('personnel.leaves.calendar');
Route::get('leaves/holidays', [LeaveController::class, 'holidays'])->name('personnel.leaves.holidays');
Route::get('leaves', [LeaveController::class, 'index'])->name('personnel.leaves.index');
Route::get('leaves/create', [LeaveController::class, 'create'])->name('personnel.leaves.create');
Route::post('leaves', [LeaveController::class, 'store'])->name('personnel.leaves.store');
Route::get('leaves/{leave}', [LeaveController::class, 'show'])->name('personnel.leaves.show');
Route::get('leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('personnel.leaves.edit');
Route::put('leaves/{leave}', [LeaveController::class, 'update'])->name('personnel.leaves.update');
Route::delete('leaves/{leave}', [LeaveController::class, 'destroy'])->name('personnel.leaves.destroy');

Route::get('tasks', [TaskController::class, 'index'])->name('personnel.tasks.index');
Route::get('tasks/history', [TaskController::class, 'history'])->name('personnel.tasks.history');
Route::get('tasks/create', [TaskController::class, 'create'])->name('personnel.tasks.create');
Route::post('tasks', [TaskController::class, 'store'])->name('personnel.tasks.store');
Route::get('tasks/{task}/edit', [TaskController::class, 'edit'])->name('personnel.tasks.edit');
Route::put('tasks/{task}', [TaskController::class, 'update'])->name('personnel.tasks.update');
Route::patch('tasks/{task}/stage', [TaskController::class, 'updateStage'])->name('personnel.tasks.update-stage');
Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('personnel.tasks.destroy');
