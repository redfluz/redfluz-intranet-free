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
    'title' => 'Task Board',
    'rma_board_title' => 'Task RMA',
    'maintenance_board_title' => 'Maintenance Tasks',
    'rma_history' => 'Task RMA History',
    'rma_history_description' => 'Successful and failed RMA and sales-order tasks.',
    'maintenance_history' => 'Maintenance Task History',
    'maintenance_history_description' => 'Successful and failed fixed-asset maintenance tasks.',
    'completed_task_locked' => 'This task is completed and is final. Changes are not allowed; open a new task for further work.',
    'add_task' => 'New Task',
    'delete_title' => 'Delete Task',
    'delete_confirm' => 'This task will be deleted together with its notes and attachments. This cannot be undone.',
    'general' => 'General',
    'related' => 'Related',
    'search_placeholder' => 'Search title or reference...',
    'all_priorities' => 'All Priorities',
    'all_assignees' => 'All Assignees',
    'all_sources' => 'All Sources',
    'open_source' => 'Open Source',
    'source_types' => [
        'fixed_asset_maintenance' => 'Maintenance',
        'rma_estimate' => 'RMA',
        'sales_order' => 'Sales Order',
        'other' => 'Other / Unlinked',
    ],
    'no_invoice' => '-- Not linked to an invoice --',
    'invoice_binding_info' => 'Optional. Link this task to an existing invoice to track it against a specific deal.',
    'stage_update_failed' => 'Could not update task stage',
    'stage_updated' => 'Task stage updated successfully.',
    'attachments' => 'Attachments',
    'view_task' => 'View Task',
    'task_details' => 'Task Details',
    'related_rma' => 'Related RMA',
    'created_by' => 'Created By',
    'completed_at' => 'Completed At',
    'mark_success' => 'Mark as Successful',
    'mark_failure' => 'Unable to Complete',
    'task_completed' => 'Task completed successfully.',
    'instructions' => 'Task Instructions',
    'no_instructions' => 'No task instructions recorded.',
    'work_notes' => 'Work Notes',
    'no_work_notes' => 'No work updates have been recorded.',
    'add_work_note' => 'Add Work Update',
    'work_note' => 'Update Details',
    'work_note_added' => 'Work update recorded successfully.',
    'work_note_placeholder' => 'Example: Inspection completed. Replacement SSD is required before repair can continue.',
    'save_work_note' => 'Record Update',
    'note_type_label' => 'Update Type',
    'completion_note' => 'Completion Outcome',
    'completion_note_placeholder' => 'Record what was completed, or explain why the task could not be completed.',
    'completion_note_help' => 'Required when changing an active task to Success or Failure.',
    'note_type' => [
        'progress' => 'Progress Update',
        'parts_required' => 'Parts Required',
        'completion' => 'Completed',
        'failure' => 'Unable to Complete',
    ],
    'assignment_managed_by_rma' => 'Assignment is controlled from the linked RMA workflow.',
    'no_notes' => 'No notes recorded.',
    'no_attachments' => 'No attachments uploaded.',
    'history' => 'Task History',
    'history_description' => 'Successful and failed tasks.',
    'active_tasks' => 'Active Tasks',
    'all_completion_statuses' => 'All Completion Statuses',
    'completed_date' => 'Completed Date',
    'history_empty' => 'No completed tasks found in history.',
    'completed_retention_notice' => 'Successful and failed tasks stay on this board for 30 days. Completed tasks are also listed in Task History.',
    'stage' => [
        'introduction' => 'Introduction',
        'follow_up_1' => 'Follow Up 1',
        'follow_up_2' => 'Follow Up 2',
        'proceed' => 'Proceed',
        'success' => 'Success',
        'failure' => 'Failure',
    ],

    'priority' => [
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
    ],

    'table' => [
        'column_reference_number' => 'Reference',
        'column_title' => 'Title',
        'column_company' => 'Company',
        'column_contact' => 'Contact',
        'column_invoice' => 'Linked Invoice',
        'column_stage' => 'Stage',
        'column_priority' => 'Priority',
        'column_due_date' => 'Due Date',
        'column_assigned_to' => 'Assigned To',
        'column_notes' => 'Notes',
        'column_actions' => 'Actions',
    ],
];
