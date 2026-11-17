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
    'actions' => [
        'add' => 'Add',
        'remove' => 'Remove',
        'edit' => 'Edit',
        'update' => 'Update',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'close' => 'Close',
        'delete' => 'Delete',
        'search' => 'Search',
        'reset' => 'Reset',
        'view' => 'View',
        'review' => 'Review',
        'view_quotation' => 'View Quotation',
        'view_purchase_order' => 'View Purchase Order',
        'view_quotation' => 'View Quotation',
        'view_purchase_order' => 'View Purchase Order',
        'send' => 'Send',
        'back' => 'Back',
        'views' => 'Views',
        'post' => 'Post',
        'approve' => 'Approve',
        'print_pdf' => 'Print PDF',
        'download' => 'Download',
        'upload' => 'Upload',
        'choose' => 'Choose',
        'next' => 'Next',
        'previous' => 'Previous',
        'complete' => 'Complete',
        'select_option' => 'Please select',
        'check_all' => 'Check All',
        'clear_all' => 'Clear All',
        'filter' => 'Filter',
        'locked' => 'Locked',
        'markAllAsRead' => 'Mark all as read',
        'export_excel' => 'Export Excel',
        'export_pdf' => 'Export PDF',
        'select_all' => 'Select All',
        'deselect_all' => 'Deselect All',
        'batch_approve' => 'Approve Selected',
        'batch_cancel' => 'Cancel Selected',
        'undo' => 'Undo',
        'confirm' => 'Confirm',
        'clone' => 'Clone',
        'action' => 'Action',
        'toggle_password_visibility' => 'Toggle password visibility',
    ],
    'status' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'draft' => 'Draft',
        'published' => 'Published',
        'archived' => 'Archived',
    ],
    'labels' => [
        'audit_trail' => 'Audit Trail',
        'login' => 'Login',
        'logout' => 'Log Out',
        'profile' => 'Profile',
        'all' => 'All',
        'not_found_record' => 'Record not found.',
        'leave_blank_password' => '(Leave blank to keep current)',
        'publish_on' => 'Publish on',
        'users' => 'Users',
        'welcome' => 'Welcome',
        'welcome_back' => 'Welcome back',
        'reorder' => 'Reorder',
        'sign_in_account' => 'Sign in to your account',
        'sign_in_dashboard' => 'Sign in to access your dashboard',
        'email_address' => 'E-mail Address',
        'username_or_email' => 'Username or email',
        'password' => 'Password',
        'forgot_password' => 'Forgot Password?',
        'created_at' => 'Created At',
        'actions' => 'Action',
        'brand' => 'Brand',
        'messages' => 'Messages',
        'system_error' => 'System Error',
        'comments' => 'Comments',
        'join_comment' => 'Join the discussion...',
        'total' => 'Total',
        'choose_file' => 'Choose File',
        'no_file_chosen' => 'No file selected',
        'system' => 'System',
        'yes' => 'Yes',
        'no' => 'No',
        'user' => 'User',
        'permitted' => 'Permitted',
    ],
    'alerts' => [
        'confirm_delete' => 'Confirm Delete',
        'confirm_delete_message' => 'Are you sure you want to delete this item?',
        'confirm_delete_yes' => 'Yes, delete it!',
        'confirm_batch_approve' => 'Approve all selected records and post to the general ledger?',
        'confirm_batch_cancel' => 'Cancel all selected records?',
        'no_selection' => 'Please select at least one record.',
    ],
    'messages' => [
        'batch_approved' => ':count record(s) approved and posted.',
        'batch_cancelled' => ':count record(s) cancelled.',
        'uncancelled' => 'Record has been restored.',
        'uncancel_confirm' => 'Are you sure you want to undo this cancellation?',
        'confirm_delete' => 'Are you sure you want to delete this record?',
    ],
    'errors' => [
        'posting_date_settings_missing' => 'Financial posting is unavailable because chart settings have not been configured.',
        'posting_date_unavailable' => 'The posting date does not belong to an available financial period.',
        'posting_date_year_closed' => 'The posting date belongs to a closed financial year.',
        'posting_date_period_closed' => 'The posting date belongs to a closed financial period.',
        'posting_date_current_period_missing' => 'Strict journal posting requires a current financial year and period in chart settings.',
        'posting_date_not_current_period' => 'Strict journal posting allows submissions only in the current financial period.',
    ],
    'sales_type' => [
        'label' => 'Sales Type',
        'product' => 'Product',
        'service' => 'Service',
        'recurring' => 'Recurring',
        'duration' => 'Duration',
    ],
    'follow_up_type' => [
        'label' => 'Follow-up Type',
        'call' => 'Call',
        'whatsapp' => 'WhatsApp',
        'sms' => 'SMS',
        'email' => 'Email',
        'meeting' => 'Meeting',
        'other' => 'Other',
    ],
];
