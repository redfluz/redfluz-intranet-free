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

return [
    'title' => 'Task Dashboard',
    'subtitle' => 'Monitor deadlines, workload, and progress across the task pipeline.',
    'eyebrow' => 'Operations / Task intelligence',

    'actions' => [
        'open_board' => 'Open Task Board',
        'new_task' => 'New Task',
        'edit_task' => 'Edit Task',
        'view_all' => 'View All',
    ],
    'summary' => [
        'open' => 'Open Tasks',
        'open_note' => 'Across active pipeline stages',
        'overdue' => 'Overdue',
        'overdue_note' => 'Past the due date',
        'due_today' => 'Due Today',
        'due_today_note' => 'Requires attention today',
        'completed' => 'Closed This Month',
        'completed_note' => 'Success and failure outcomes',
    ],
    'sections' => [
        'throughput' => 'Throughput',
        'throughput_note' => 'Tasks created versus closed over the last six months.',
        'workload' => 'Workload',
        'workload_note' => 'Open tasks by assignee.',
        'pipeline' => 'Pipeline Overview',
        'pipeline_note' => 'Current number of tasks in each stage.',
        'priority' => 'Open Tasks by Priority',
        'priority_note' => 'Priority mix for active work.',
        'attention' => 'Needs Attention',
        'attention_note' => 'Overdue tasks and tasks due within seven days.',
        'my_tasks' => 'My Open Tasks',
        'my_tasks_note' => 'Active tasks assigned to you.',
        'recent_closed' => 'Recently Closed',
        'recent_closed_note' => 'Latest success and failure outcomes.',
    ],
    'labels' => [
        'created' => 'Created',
        'closed' => 'Closed',
        'last_6_months' => 'Last 6 months',
        'no_workload' => 'No open tasks to distribute.',
        'unassigned' => 'Unassigned',
        'no_company' => 'No company',
        'no_due_date' => 'No due date',
        'due_today' => 'Due today',
        'overdue_by' => 'Overdue :days day|Overdue :days days',
        'due_in' => 'Due in :days day|Due in :days days',
        'closed_on' => 'Closed :date',
        'tasks' => ':count task|:count tasks',
    ],
    'empty' => [
        'attention' => 'No overdue or upcoming tasks require attention.',
        'my_tasks' => 'No open tasks are assigned to you.',
        'recent_closed' => 'No tasks have been closed yet.',
    ],
];
