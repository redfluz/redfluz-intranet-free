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

use App\Http\Controllers\Leave\HolidayController;
use App\Http\Controllers\Leave\LeaveManagementController;
use Illuminate\Support\Facades\Route;

// Administrators approve or reject leave applications submitted through the
// personnel workspace. There are no leave types or entitlement settings in
// the intranet edition.
Route::get('leave-managements', [LeaveManagementController::class, 'index'])->name('leave.leave-managements.index');
Route::get('leave-managements/{leave}', [LeaveManagementController::class, 'show'])->name('leave.leave-managements.show');
Route::post('leave-managements/{leave}/approve', [LeaveManagementController::class, 'approve'])->name('leave.leave-managements.approve');
Route::post('leave-managements/{leave}/reject', [LeaveManagementController::class, 'reject'])->name('leave.leave-managements.reject');

Route::get('holidays', [HolidayController::class, 'index'])->name('leave.holidays.index');
Route::get('holidays/create', [HolidayController::class, 'create'])->name('leave.holidays.create');
Route::post('holidays', [HolidayController::class, 'store'])->name('leave.holidays.store');
Route::get('holidays/{holiday}/edit', [HolidayController::class, 'edit'])->name('leave.holidays.edit');
Route::put('holidays/{holiday}', [HolidayController::class, 'update'])->name('leave.holidays.update');
Route::delete('holidays/{holiday}', [HolidayController::class, 'destroy'])->name('leave.holidays.destroy');
