<?php

use App\Http\Controllers\Attendance\AttendanceController;
use App\Http\Controllers\Attendance\AttendanceManagementController;
use Illuminate\Support\Facades\Route;

Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
Route::get('attendance-managements', [AttendanceManagementController::class, 'index'])
    ->name('attendance.attendance-managements.index');
Route::post('attendance/clock-in', [AttendanceController::class, 'clockIn'])->name('attendance.clock-in');
Route::post('attendance/clock-out', [AttendanceController::class, 'clockOut'])->name('attendance.clock-out');
