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

namespace Database\Seeders;

use App\Enums\FileResources;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clerkPagePermissions = [];

        $structure = [
            // 1. Personnel (staff workspace + news)
            'PERSONNEL' => [
                'icon' => 'user',
                'access' => ['Super Admin', 'Manager', 'Editor', 'User', 'Finance', 'Human', 'Technician'],
                'pages' => [
                    [
                        'name' => 'DASHBOARD',
                        'route_name' => FileResources::PERSONNEL_DASHBOARD->value,
                        'icon' => 'layout-dashboard',
                        'access' => [
                            'Super Admin' => ['can' => ['read']],
                            'Manager' => ['can' => ['read']],
                            'Editor' => ['can' => ['read']],
                            'User' => ['can' => ['read']],
                            'Finance' => ['can' => ['read']],
                            'Human' => ['can' => ['read']],
                            'Technician' => ['can' => ['read']],
                        ],
                    ],
                    [
                        'name' => 'NOTIFICATIONS',
                        'route_name' => FileResources::NOTIFICATIONS->value,
                        'icon' => 'bell',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'remove']],
                            'Manager' => ['can' => ['read', 'remove']],
                            'Editor' => ['can' => ['read', 'remove']],
                            'User' => ['can' => ['read', 'remove']],
                            'Finance' => ['can' => ['read', 'remove']],
                            'Human' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                    [
                        'name' => 'MESSAGE',
                        'route_name' => FileResources::MESSAGES->value,
                        'icon' => 'message-square',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Manager' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Editor' => ['can' => ['read', 'add', 'update', 'remove']],
                            'User' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Finance' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Human' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                    [
                        'name' => 'TASKS',
                        'route_name' => FileResources::TASKS->value,
                        'icon' => 'check-square',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Manager' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Editor' => ['can' => ['read', 'add', 'update', 'remove']],
                            'User' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Finance' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Human' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Technician' => ['can' => ['read', 'update']],
                        ],
                    ],
                    [
                        'name' => 'TASK DASHBOARD',
                        'route_name' => FileResources::TASK_DASHBOARD->value,
                        'icon' => 'layout-dashboard',
                        'access' => [
                            'Super Admin' => ['can' => ['read']],
                            'Manager' => ['can' => ['read']],
                            'Editor' => ['can' => ['read']],
                            'User' => ['can' => ['read']],
                            'Finance' => ['can' => ['read']],
                            'Human' => ['can' => ['read']],
                            'Technician' => ['can' => ['read']],
                        ],
                    ],
                    [
                        'name' => 'TASK HISTORY',
                        'route_name' => FileResources::PERSONNEL_TASK_HISTORY->value,
                        'icon' => 'history',
                        'access' => [
                            'Super Admin' => ['can' => ['read']],
                            'Manager' => ['can' => ['read']],
                            'Editor' => ['can' => ['read']],
                            'User' => ['can' => ['read']],
                            'Finance' => ['can' => ['read']],
                            'Human' => ['can' => ['read']],
                            'Technician' => ['can' => ['read']],
                        ],
                    ],
                    [
                        'name' => 'LEAVES',
                        'route_name' => FileResources::LEAVES->value,
                        'icon' => 'calendar-off',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Manager' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Editor' => ['can' => ['read', 'add', 'update', 'remove']],
                            'User' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Finance' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Human' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                    [
                        'name' => 'ATTENDANCE',
                        'route_name' => FileResources::ATTENDANCE->value,
                        'icon' => 'clock-3',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update']],
                            'Manager' => ['can' => ['read', 'add', 'update']],
                            'Editor' => ['can' => ['read', 'add', 'update']],
                            'User' => ['can' => ['read', 'add', 'update']],
                            'Finance' => ['can' => ['read', 'add', 'update']],
                            'Human' => ['can' => ['read', 'add', 'update']],
                            'Technician' => ['can' => ['read', 'add', 'update']],
                        ],
                    ],
                    [
                        'name' => 'HOLIDAYS',
                        'route_name' => FileResources::PERSONNEL_HOLIDAYS->value,
                        'icon' => 'calendar-days',
                        'access' => [
                            'Super Admin' => ['can' => ['read']],
                            'Manager' => ['can' => ['read']],
                            'Editor' => ['can' => ['read']],
                            'User' => ['can' => ['read']],
                            'Finance' => ['can' => ['read']],
                            'Human' => ['can' => ['read']],
                        ],
                    ],
                    [
                        'name' => 'CLAIM',
                        'route_name' => FileResources::CLAIMS->value,
                        'icon' => 'receipt-text',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Manager' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Editor' => ['can' => ['read', 'add', 'update', 'remove']],
                            'User' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Finance' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Human' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                    [
                        'name' => 'NEWS',
                        'route_name' => FileResources::NEWS->value,
                        'icon' => 'newspaper',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove', 'approve', 'cancel']],
                            'Editor' => ['can' => ['read', 'add', 'update', 'remove', 'approve', 'cancel']],
                        ],
                    ],
                    [
                        'name' => 'CATEGORY',
                        'route_name' => FileResources::NEWS_CATEGORIES->value,
                        'icon' => 'tags',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Editor' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                ],
            ],

            // 2. Human Resources (leave & claim administration — approve or
            //    reject only; no types and no settings in the intranet edition)
            'HUMAN_RESOURCES' => [
                'icon' => 'users',
                'access' => ['Test HR', 'Super Admin', 'Manager', 'Human', 'Finance'],
                'pages' => [
                    [
                        'name' => 'LEAVE',
                        'route_name' => FileResources::LEAVE_MANAGEMENTS->value,
                        'icon' => 'user-minus',
                        'access' => [
                            'Test HR' => ['can' => ['read', 'approve', 'cancel']],
                            'Super Admin' => ['can' => ['read', 'approve', 'cancel']],
                            'Human' => ['can' => ['read', 'approve', 'cancel']],
                            'Manager' => ['can' => ['read', 'approve', 'cancel']],
                            'Finance' => ['can' => ['read', 'approve', 'cancel']],
                        ],
                    ],
                    [
                        'name' => 'HOLIDAYS',
                        'route_name' => FileResources::HOLIDAYS->value,
                        'icon' => 'calendar-days',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                            'Human' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                    [
                        'name' => 'CLAIM',
                        'route_name' => FileResources::CLAIM_MANAGEMENTS->value,
                        'icon' => 'file-check-2',
                        'access' => [
                            'Test HR' => ['can' => ['read', 'approve', 'cancel']],
                            'Super Admin' => ['can' => ['read', 'approve', 'cancel']],
                            'Manager' => ['can' => ['read', 'approve', 'cancel']],
                            'Finance' => ['can' => ['read', 'approve', 'cancel']],
                            'Human' => ['can' => ['read', 'approve', 'cancel']],
                        ],
                    ],
                    [
                        'name' => 'ATTENDANCE',
                        'route_name' => FileResources::ATTENDANCE_MANAGEMENTS->value,
                        'icon' => 'calendar-clock',
                        'access' => [
                            'Test HR' => ['can' => ['read']],
                            'Super Admin' => ['can' => ['read']],
                            'Manager' => ['can' => ['read']],
                            'Human' => ['can' => ['read']],
                            'Finance' => ['can' => ['read']],
                        ],
                    ],
                ],
            ],

            // 3. System (roles, users, notifications, settings)
            'SYSTEM' => [
                'icon' => 'settings',
                'access' => ['Super Admin'],
                'pages' => [
                    [
                        'name' => 'ROLE',
                        'route_name' => FileResources::ROLES->value,
                        'icon' => 'badge-check',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                    [
                        'name' => 'USER',
                        'route_name' => FileResources::USERS->value,
                        'icon' => 'users-round',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                    [
                        'name' => 'NOTIFICATIONS',
                        'route_name' => FileResources::SYSTEM_NOTIFICATIONS->value,
                        'icon' => 'megaphone',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'add', 'update', 'remove']],
                        ],
                    ],
                    [
                        'name' => 'SETTINGS',
                        'route_name' => FileResources::SETTINGS->value,
                        'icon' => 'wrench',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'update']],
                        ],
                    ],
                ],
            ],

            // 4. Menu (permissions only — folders, pages and groupings are
            //    seeded, not managed through the UI)
            'MENU' => [
                'icon' => 'menu',
                'access' => ['Super Admin'],
                'pages' => [
                    [
                        'name' => 'FOLDER PERMISSION',
                        'route_name' => FileResources::FOLDER_PERMISSIONS->value,
                        'icon' => 'folder-cog',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'update']],
                        ],
                    ],
                    [
                        'name' => 'PAGE PERMISSION',
                        'route_name' => FileResources::PAGE_PERMISSIONS->value,
                        'icon' => 'file-check-2',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'update']],
                        ],
                    ],
                    [
                        'name' => 'PAGE GROUPING PERMISSION',
                        'route_name' => FileResources::PAGE_GROUPING_PERMISSIONS->value,
                        'icon' => 'shield-check',
                        'access' => [
                            'Super Admin' => ['can' => ['read', 'update']],
                        ],
                    ],
                ],
            ],
        ];

        // Every role listed in PERSONNEL represents an employee and receives full
        // access to the personnel workspace pages.
        $staffActions = ['read', 'add', 'update', 'remove', 'approve', 'cancel'];
        foreach ($structure['PERSONNEL']['pages'] as &$staffPage) {
            foreach ($structure['PERSONNEL']['access'] as $staffRole) {
                $staffPage['access'][$staffRole] = ['can' => $staffActions];
            }
        }
        unset($staffPage);

        // HR roles need the complete HR workspace, not only selected pages.
        // The leave and claim grids are decision-only: read plus approve and
        // reject (cancel), never add/update/remove.
        $hrRoles = ['Test HR', 'Human'];
        $decisionOnlyRoutes = [
            FileResources::LEAVE_MANAGEMENTS->value,
            FileResources::CLAIM_MANAGEMENTS->value,
            FileResources::ATTENDANCE_MANAGEMENTS->value,
        ];
        foreach (['HUMAN_RESOURCES'] as $hrFolder) {
            $structure[$hrFolder]['access'] = array_values(array_unique([
                ...$structure[$hrFolder]['access'],
                ...$hrRoles,
            ]));

            if (! isset($structure[$hrFolder]['pages'])) {
                continue;
            }

            foreach ($structure[$hrFolder]['pages'] as &$hrPage) {
                $hrActions = in_array($hrPage['route_name'], $decisionOnlyRoutes, true)
                    ? ['read', 'approve', 'cancel']
                    : $staffActions;

                foreach ($hrRoles as $hrRole) {
                    $hrPage['access'][$hrRole] = ['can' => $hrActions];
                }
            }
            unset($hrPage);
        }

        $pageGroupingsMap = [
            'PERSONNEL' => [
                'OVERVIEW' => ['icon' => 'layout-dashboard', 'pages' => ['DASHBOARD', 'NOTIFICATIONS', 'MESSAGE']],
                'WORK' => ['icon' => 'check-square', 'pages' => ['TASK DASHBOARD', 'TASKS', 'TASK HISTORY']],
                'TIME & ATTENDANCE' => ['icon' => 'calendar-clock', 'pages' => ['LEAVES', 'ATTENDANCE', 'HOLIDAYS']],
                'COMPENSATION' => ['icon' => 'wallet', 'pages' => ['CLAIM']],
                'NEWS' => ['icon' => 'newspaper', 'pages' => ['NEWS', 'CATEGORY']],
            ],
            'HUMAN_RESOURCES' => [
                'LEAVE_MANAGEMENT' => ['icon' => 'calendar', 'pages' => ['LEAVE', 'HOLIDAYS']],
                'CLAIM_OPS' => ['icon' => 'receipt', 'pages' => ['CLAIM']],
                'ATTENDANCE' => ['icon' => 'calendar-clock', 'pages' => ['ATTENDANCE']],
            ],
            'SYSTEM' => [
                'ACCESS CONTROL' => ['icon' => 'shield-check', 'pages' => ['ROLE', 'USER']],
                'MONITORING' => ['icon' => 'activity', 'pages' => ['NOTIFICATIONS']],
                'CONFIGURATION' => ['icon' => 'settings', 'pages' => ['SETTINGS']],
            ],
            'MENU' => [
                'PERMISSIONS' => ['icon' => 'shield-check', 'pages' => ['FOLDER PERMISSION', 'PAGE PERMISSION', 'PAGE GROUPING PERMISSION']],
            ],
        ];

        $roleIds = DB::table('roles')
            ->whereIn('name', ['Super Admin', 'Manager', 'Editor', 'User', 'Finance', 'Human', 'Sales', 'Technician', 'Clerk', 'Test Finance', 'Test AP', 'Test AR', 'Test HR'])
            ->pluck('id', 'name');

        // Build a reverse lookup: folderName => pageName => pageId[]
        // Arrays are used because merged folders may contain pages with the
        // same display name originating from different source folders.
        $pageIdLookup = [];

        $seededRouteNames = [];

        $folderOrder = 0;

        foreach ($structure as $folderName => $folderData) {
            $folderOrder++;

            $existingFolder = DB::table('folders')->where('name', $folderName)->first();
            $folderId = $existingFolder->id ?? (string) Str::uuid7();

            DB::table('folders')->updateOrInsert(
                ['name' => $folderName],
                [
                    'id' => $folderId,
                    'icon' => $folderData['icon'],
                    'order' => $existingFolder->order ?? $folderOrder,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            foreach ($roleIds as $roleName => $roleId) {
                $existingPermission = DB::table('folder_permissions')
                    ->where('role_id', $roleId)
                    ->where('folder_id', $folderId)
                    ->first();

                DB::table('folder_permissions')->updateOrInsert(
                    ['role_id' => $roleId, 'folder_id' => $folderId],
                    [
                        'id' => $existingPermission->id ?? (string) Str::uuid7(),
                        'can_read' => in_array($roleName, $folderData['access'], true),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            foreach ($folderData['pages'] as $pageIndex => $pageData) {
                $seededRouteNames[] = $pageData['route_name'];

                $existingPage = DB::table('pages')
                    ->where('route_name', $pageData['route_name'])
                    ->first();
                $pageId = $existingPage->id ?? (string) Str::uuid7();

                DB::table('pages')->updateOrInsert(
                    ['id' => $pageId],
                    [
                        'folder_id' => $folderId,
                        'name' => $pageData['name'],
                        'route_name' => $pageData['route_name'],
                        'icon' => $pageData['icon'],
                        'order' => $pageIndex + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $pageIdLookup[$folderName][$pageData['name']][] = $pageId;

                foreach ($roleIds as $roleName => $roleId) {
                    $can = $pageData['access'][$roleName]['can']
                        ?? ($roleName === 'Clerk' ? ($clerkPagePermissions[$pageData['route_name']] ?? []) : []);
                    $existingPermission = DB::table('page_permissions')
                        ->where('role_id', $roleId)
                        ->where('page_id', $pageId)
                        ->first();

                    DB::table('page_permissions')->updateOrInsert(
                        ['role_id' => $roleId, 'page_id' => $pageId],
                        [
                            'id' => $existingPermission->id ?? (string) Str::uuid7(),
                            'can_read' => in_array('read', $can, true),
                            'can_add' => in_array('add', $can, true),
                            'can_update' => in_array('update', $can, true),
                            'can_remove' => in_array('remove', $can, true),
                            'can_approve' => in_array('approve', $can, true),
                            'can_cancel' => in_array('cancel', $can, true),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }

        // ── Seed page_groupings and assign page_grouping_id to pages ──
        $groupingIdLookup = [];

        foreach ($pageGroupingsMap as $folderName => $groupings) {
            $folder = DB::table('folders')->where('name', $folderName)->first();
            if (! $folder) {
                continue;
            }

            $order = 1;
            foreach ($groupings as $groupingName => $groupingData) {
                $existingGrouping = DB::table('page_groupings')
                    ->where('name', $groupingName)
                    ->where('folder_id', $folder->id)
                    ->first();
                $groupingId = $existingGrouping->id ?? (string) Str::uuid7();

                DB::table('page_groupings')->updateOrInsert(
                    ['id' => $groupingId],
                    [
                        'folder_id' => $folder->id,
                        'name' => $groupingName,
                        'icon' => $groupingData['icon'],
                        'order' => $order,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );

                $groupingIdLookup[$folderName][$groupingName] = $groupingId;

                // Assign page_grouping_id to each page in this grouping.
                // array_shift consumes one ID per reference so that duplicate
                // page names (e.g. two "SETTING" pages in HUMAN_RESOURCES)
                // are distributed across their respective groupings.
                $groupingPageIds = [];
                foreach ($groupingData['pages'] as $pageName) {
                    if (! isset($pageIdLookup[$folderName][$pageName])) {
                        throw new RuntimeException("Missing page '{$pageName}' in folder '{$folderName}'.");
                    }
                    $pageId = array_shift($pageIdLookup[$folderName][$pageName]);
                    if ($pageId) {
                        DB::table('pages')
                            ->where('id', $pageId)
                            ->update([
                                'page_grouping_id' => $groupingId,
                                'updated_at' => now(),
                            ]);
                        $groupingPageIds[] = $pageId;
                    }
                }

                // Seed page_grouping_permissions for each role
                foreach ($roleIds as $roleName => $roleId) {
                    // A role can access a grouping if it has folder access
                    $folderHasAccess = in_array($roleName, $structure[$folderName]['access'], true);

                    // Check if the role has read access to at least one page in this grouping
                    $hasPageAccess = false;
                    foreach ($groupingPageIds as $pid) {
                        $perm = DB::table('page_permissions')
                            ->where('role_id', $roleId)
                            ->where('page_id', $pid)
                            ->first();
                        if ($perm && $perm->can_read) {
                            $hasPageAccess = true;
                            break;
                        }
                    }

                    $existingPerm = DB::table('page_grouping_permissions')
                        ->where('role_id', $roleId)
                        ->where('page_grouping_id', $groupingId)
                        ->first();

                    DB::table('page_grouping_permissions')->updateOrInsert(
                        ['role_id' => $roleId, 'page_grouping_id' => $groupingId],
                        [
                            'id' => $existingPerm->id ?? (string) Str::uuid7(),
                            'can_read' => $folderHasAccess && $hasPageAccess,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }

                $order++;
            }
        }

        // Remove folders that are not part of the intranet structure.
        $orphanedFolders = DB::table('folders')
            ->whereNotIn('name', ['PERSONNEL', 'HUMAN_RESOURCES', 'SYSTEM', 'MENU'])
            ->pluck('id');

        if ($orphanedFolders->isNotEmpty()) {
            DB::table('page_grouping_permissions')
                ->whereIn('page_grouping_id', function ($q) use ($orphanedFolders) {
                    $q->select('id')->from('page_groupings')->whereIn('folder_id', $orphanedFolders);
                })->delete();

            DB::table('page_permissions')
                ->whereIn('page_id', function ($q) use ($orphanedFolders) {
                    $q->select('id')->from('pages')->whereIn('folder_id', $orphanedFolders);
                })->delete();

            DB::table('page_groupings')
                ->whereIn('folder_id', $orphanedFolders)->delete();

            DB::table('pages')
                ->whereIn('folder_id', $orphanedFolders)->delete();

            DB::table('folder_permissions')
                ->whereIn('folder_id', $orphanedFolders)->delete();

            DB::table('folders')
                ->whereIn('id', $orphanedFolders)->delete();
        }

        // Remove pages that are no longer part of the intranet structure
        // (e.g. the removed leave/claim settings pages) and their permissions.
        $orphanedPages = DB::table('pages')
            ->whereNotIn('route_name', $seededRouteNames)
            ->pluck('id');

        if ($orphanedPages->isNotEmpty()) {
            DB::table('page_permissions')
                ->whereIn('page_id', $orphanedPages)->delete();

            DB::table('pages')
                ->whereIn('id', $orphanedPages)->delete();
        }

        // Remove groupings that are no longer part of the intranet structure
        // (e.g. the removed LEAVE_CONFIG and CLAIM_CONFIG groupings).
        foreach ($pageGroupingsMap as $folderName => $groupings) {
            $folder = DB::table('folders')->where('name', $folderName)->first();
            if (! $folder) {
                continue;
            }

            $orphanedGroupings = DB::table('page_groupings')
                ->where('folder_id', $folder->id)
                ->whereNotIn('name', array_keys($groupings))
                ->pluck('id');

            if ($orphanedGroupings->isNotEmpty()) {
                DB::table('page_grouping_permissions')
                    ->whereIn('page_grouping_id', $orphanedGroupings)->delete();

                DB::table('page_groupings')
                    ->whereIn('id', $orphanedGroupings)->delete();
            }
        }
    }
}
