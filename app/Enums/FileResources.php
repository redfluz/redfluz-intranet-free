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

namespace App\Enums;

/**
 * Permission resources used by the intranet menu and controllers.
 *
 * The route_name stored on each page matches one of these values.
 */
enum FileResources: string
{
    // Claim
    case CLAIM_MANAGEMENTS = 'claim.claim-managements.index';

    // Leave
    case HOLIDAYS = 'leave.holidays.index';
    case LEAVE_MANAGEMENTS = 'leave.leave-managements.index';

    // Menu / Navigation
    case FOLDER = 'menu.folders.index';
    case FOLDER_PERMISSIONS = 'menu.folder-permissions.index';
    case PAGE_PERMISSIONS = 'menu.page-permissions.index';
    case PAGE_GROUPING_PERMISSIONS = 'menu.page-grouping-permissions.index';

    // News
    case NEWS = 'news.news.index';
    case NEWS_CATEGORIES = 'news.news-categories.index';

    // Personnel
    case PERSONNEL_DASHBOARD = 'personnel.dashboard.index';
    case CLAIMS = 'personnel.claims.index';
    case LEAVES = 'personnel.leaves.index';
    case ATTENDANCE = 'attendance.index';
    case ATTENDANCE_MANAGEMENTS = 'attendance.attendance-managements.index';
    case PERSONNEL_HOLIDAYS = 'personnel.leaves.holidays';
    case MESSAGES = 'personnel.messages.index';
    case NEWS_COMMENTS = 'personnel.comments.index';
    case TASKS = 'personnel.tasks.index';
    case PERSONNEL_TASK_HISTORY = 'personnel.tasks.history';
    case NOTIFICATIONS = 'personnel.notifications.index';

    // System & Users
    case ROLES = 'system.roles.index';
    case SETTINGS = 'system.settings.index';
    case SYSTEM_NOTIFICATIONS = 'system.notifications.index';
    case USERS = 'system.users.index';

    // Tasks
    case TASK_DASHBOARD = 'task.dashboard.index';

    /**
     * Get the human-readable resource name.
     */
    public function displayName(): string
    {
        return match ($this) {
            self::CLAIM_MANAGEMENTS => 'Claim Management',

            self::HOLIDAYS => 'Holiday',
            self::LEAVE_MANAGEMENTS => 'Leave Management',

            self::FOLDER => 'Folder',
            self::FOLDER_PERMISSIONS => 'Folder Permission',
            self::PAGE_PERMISSIONS => 'Page Permission',
            self::PAGE_GROUPING_PERMISSIONS => 'Page Grouping Permission',

            self::NEWS => 'News',
            self::NEWS_CATEGORIES => 'Category',

            self::PERSONNEL_DASHBOARD => 'Personnel Dashboard',
            self::CLAIMS => 'Claim',
            self::LEAVES => 'Leave',
            self::ATTENDANCE => 'Attendance',
            self::ATTENDANCE_MANAGEMENTS => 'Attendance Records',
            self::PERSONNEL_HOLIDAYS => 'Holiday Calendar',
            self::MESSAGES => 'Chat',
            self::NEWS_COMMENTS => 'News Comment',
            self::TASKS => 'Task',
            self::PERSONNEL_TASK_HISTORY => 'My Task History',
            self::NOTIFICATIONS => 'User Notification',

            self::ROLES => 'Role',
            self::SETTINGS => 'Setting',
            self::SYSTEM_NOTIFICATIONS => 'Notification',
            self::USERS => 'User',

            self::TASK_DASHBOARD => 'Task Dashboard',
        };
    }
}
