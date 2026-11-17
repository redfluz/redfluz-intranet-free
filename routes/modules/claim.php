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

use App\Http\Controllers\Claim\ClaimManagementController;
use Illuminate\Support\Facades\Route;

// Administrators approve or reject claims submitted through the personnel
// workspace. There are no claim types or limit settings in the intranet edition.
Route::get('claim-managements', [ClaimManagementController::class, 'index'])->name('claim.claim-managements.index');
Route::get('claim-managements/{claim}', [ClaimManagementController::class, 'show'])->name('claim.claim-managements.show');
Route::post('claim-managements/{claim}/approve', [ClaimManagementController::class, 'approve'])->name('claim.claim-managements.approve');
Route::post('claim-managements/{claim}/reject', [ClaimManagementController::class, 'reject'])->name('claim.claim-managements.reject');
