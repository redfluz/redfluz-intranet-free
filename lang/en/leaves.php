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
    'general' => 'General',
    'employee' => 'Employee',
    'approval' => 'Approval',
    'employee_email' => 'Email',
    'employee_role' => 'Role',

    'dashboard' => [
        'title' => 'Leave dashboard',
        'subtitle' => 'Manage your leave applications.',
        'apply_button' => 'Apply for Leave',
        'submit_button' => 'Submit Request',
        'cancel_button' => 'Cancel',
        'details_button' => 'Details',
        'pending_requests' => 'Pending Requests',
        'recent_requests' => 'Recent Leave Requests',
        'entitlements' => 'Leave Entitlements',
        'on_leave_this_week' => 'On Leave This Week',
        'no_requests' => 'No leave requests yet.',
        'no_one_on_leave' => 'No one else is on leave this week.',
        'select_type' => 'Select leave type',
        'from_date' => 'From Date',
        'to_date' => 'To Date',
        'reason_placeholder' => 'Brief reason...',
        'confirm_cancel' => 'Cancel this leave request?',
        'days_suffix' => 'Days',
        'remaining_suffix' => 'Remaining',
        'balance_option' => ':remaining day(s) remaining',
        'balance_details' => 'Available balance: :remaining of :entitled day(s)',
        'balance_warning_title' => 'Leave balance exceeded',
        'balance_warning_message' => 'This request uses :requested day(s), but only :remaining day(s) remain for :type.',
        'all_holiday_error' => 'The selected dates fall entirely on weekends/holidays — there are no working days to apply leave for.',
        'insufficient_balance_error' => 'Insufficient :type balance — only :remaining day(s) remaining this year.',
    ],

    'admin' => [
        'title' => 'Leave Applications',
        'search_placeholder' => 'Search reference or employee...',
        'all_types' => 'All Types',
        'all_status' => 'All Status',
        'no_results' => 'No leave applications found.',
        'approve' => 'Approve',
        'reject' => 'Reject',
        'reject_title' => 'Reject Leave Application',
        'rejection_reason' => 'Rejection Reason',
        'confirm_approve' => 'Approve this leave application?',
        'show_title' => 'Leave Application Details',
        'applied_on' => 'Applied On',
        'approve_title' => 'Approve Leave Application',
        'decided_by' => 'Decided By',
        'attachment' => 'Attachment',
        'batch_approved' => 'Selected leave applications approved.',
        'batch_rejected' => 'Selected leave applications rejected.',
        'batch_approve' => 'Approve Selected',
        'batch_reject' => 'Reject Selected',
        'batch_reject_title' => 'Reject Selected Leave Applications',
        'batch_rejection_reason' => 'Rejection reason',
        'no_selection_title' => 'No applications selected',
        'no_selection' => 'Select at least one pending application.',
        'required_title' => 'Reason required',
        'rejection_reason_required' => 'Enter a rejection reason before continuing.',
        'confirm_batch_title' => 'Confirm action',
        'confirm_approve_selected' => 'Approve the selected leave applications?',
        'confirm_reject_selected' => 'Reject the selected leave applications?',
        'confirm_proceed' => 'Proceed',
        'decided_by' => 'Decided By',
    ],

    'notifications' => [
        'approved_title' => 'Leave application approved',
        'approved_message' => 'Leave application :reference has been approved.',
        'rejected_title' => 'Leave application rejected',
        'rejected_message' => 'Leave application :reference has been rejected. Reason: :reason',
    ],

    'settings' => [
        'title' => 'Leave Settings',
        'description' => 'Set the default number of days each employee is entitled to per calendar year for each leave type.',
        'days_per_annum' => 'days / year',
        'overrides_title' => 'Per-Employee Overrides',
        'overrides_description' => 'Give a specific employee a different entitlement than the default (e.g. extra annual leave for senior staff).',
        'no_overrides' => 'No overrides configured — everyone uses the defaults.',
    ],

    'calendar' => [
        'title' => 'Leave Calendar',
        'subtitle' => 'See approved leave for everyone across the organisation.',
        'previous_month' => 'Previous month',
        'next_month' => 'Next month',
        'month' => 'Month',
        'employee' => 'Employee',
        'department' => 'Department',
        'leave_type' => 'Leave type',
        'leave_dates' => 'Leave dates',
        'schedule_list' => 'Staff Leave List',
        'no_leave' => 'No approved leave is scheduled for this month.',
        'holiday_legend' => 'Holiday',
        'on_leave_legend' => 'On leave',
        'more' => 'more',
    ],

    'type' => [
        'annual' => 'Annual Leave',
        'sick' => 'Sick Leave',
        'casual' => 'Casual Leave',
        'hospitalisation' => 'Hospital Leave',
        'maternity' => 'Maternity Leave',
        'paternity' => 'Paternity Leave',
        'compassionate' => 'Comppassionate Leave',
        'marriage' => 'Marriage Leave',
        'unpaid' => 'Unpaid Leave',
    ],

    'status' => [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],

    'validation' => [
        'payroll_locked' => 'The payroll period :month is already processed or posted. Leave changes for this period are locked.',
    ],

    'table' => [
        'column_reference_number' => 'Reference',
        'column_employee' => 'Employee',
        'column_type' => 'Type',
        'column_dates' => 'Dates',
        'column_start_date' => 'Start Date',
        'column_end_date' => 'End Date',
        'column_days' => 'Days',
        'column_status' => 'Status',
        'column_reason' => 'Reason',
        'column_action' => 'Action',
    ],
];
