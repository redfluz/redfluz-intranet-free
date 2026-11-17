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
    'title' => 'Claims',
    'subtitle' => 'Submit and monitor your expense claims.',
    'edit_title' => 'Edit Claim',
    'pending_request' => 'Pending Requests',
    'currency' => 'MYR',
    'table' => [
        'column_reference_number' => 'Reference Number',
        'column_employee' => 'Employee',
        'column_claim_type' => 'Claim Type',
        'column_receipt_date' => 'Receipt Date',
        'column_receipt_no' => 'Receipt Number',
        'column_amount' => 'Amount',
        'column_project' => 'Project',
        'column_vehicle' => 'Vehicle',
        'column_rate' => 'Rate',
        'column_miles' => 'Miles',
        'column_claimant' => 'Claimant',
        'column_appointed' => 'Appointed',
        'column_clinic' => 'Clinic',
        'column_customer_name' => 'Customer Name',
        'column_customer_phone' => 'Customer Phone',
        'column_customer_email' => 'Customer Email',
        'column_description' => 'Description',
        'column_attachment' => 'Attachment',
        'column_status' => 'Status',
        'column_approved_at' => 'Approved At',
        'column_rejection_reason' => 'Rejection Reason',
    ],
    'types' => [
        'normal' => 'Normal Claim',
        'project' => 'Project Claim',
        'vehicle' => 'Vehicle Claim',
        'clinic' => 'Medical Claim',
        'customer' => 'Customer Claim',
    ],
    'status' => [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'pending' => 'Pending',
        'approved' => 'Approved',
        'paid' => 'Paid',
        'posted' => 'Posted',
        'rejected' => 'Rejected',
    ],
    'wizard' => [
        'claim_details' => 'Claim Details',
        'additional_info' => 'Additional Information',
        'step_1_details' => 'Step 1: Basic Claim Details',
        'step_2_details' => 'Step 2: Additional Information',
        'current_file' => 'Current File',
        'view_attachment' => 'View Attachment',
        'accepted_formats' => 'Accepted formats: PDF, JPG, JPEG, PNG. Maximum size: 5 MB.',
        'next_step' => 'Next Step',
        'previous_step' => 'Previous Step',
        'client_company' => 'Client Company',
        'select_company' => 'Select Company',
        'contact_person' => 'Contact Person',
        'select_contact' => 'Select Contact',
        'save_draft' => 'Save as Draft',
        'update_submit' => 'Update and Submit',
    ],
    'create' => [
        'submit_new_claim' => 'Submit New Claim',
        'step' => 'Step',
        'of' => 'of',
        'step_1_category' => 'Step 1: Select Claim Category',
        'select_claim_type' => 'Select Claim Type',
        'step_2_financial' => 'Step 2: Financial Details',
        'transaction_total' => 'Transaction Total',
        'transaction_tax' => 'Transaction Tax',
        'standard_rate' => 'Standard Rate',
        'zero_rate' => 'Zero Rate',
        'transaction_net' => 'Transaction Net',
        'override_exchange_rate' => 'Override Exchange Rate',
        'base_total_amount' => 'Base Total Amount',
        'step_3_expense' => 'Step 3: Expense Information',
        'optional' => 'Optional',
        'cancel' => 'Cancel',
        'back' => 'Back',
        'continue' => 'Continue',
        'submit_claim' => 'Submit Claim',
        'validation_errors' => 'Please correct the following errors:',
        'validation_warning_title' => 'Please review your claim',
        'maximum_amount' => 'Maximum allowed: MYR :amount.',
        'unlimited_amount' => 'There is no maximum amount for this claim type.',
        'limit_not_configured' => 'No claim limit is configured for your organisation position.',
    ],
    'placeholders' => [
        'vehicle' => 'For example: ABC 1234',
        'patient_name' => 'Patient name',
        'doctor' => 'Doctor or specialist',
        'clinic' => 'Clinic or hospital',
        'customer_name' => 'Customer name',
        'phone' => 'Phone number',
        'email' => 'customer@example.com',
        'project' => 'Enter project details',
        'description' => 'Enter the claim purpose or details',
    ],
    'settings' => [
        'title' => 'Claim Settings',
        'subtitle' => 'Configure the expense account and organisation limits for each claim type.',
        'edit_title' => 'Edit Claim Setting',
        'unlimited' => 'Unlimited',
        'table' => [
            'column_claim_type' => 'Claim Type',
            'column_expense_account' => 'Expense Account',
            'column_max_amount' => 'Maximum Claim Amount',
            'column_organization' => 'Organisation Position',
            'column_organization_limits' => 'Organisation Limits',
        ],
        'form' => [
            'expense_account' => 'Expense Account',
            'max_amount' => 'Maximum Claim Amount',
            'max_amount_hint' => 'Leave blank when this claim type has no maximum amount.',
            'organization_limits' => 'Organisation Claim Limits',
            'organization_limits_hint' => 'Set a different maximum for each organisation position. Leave an amount blank for unlimited.',
        ],
        'validation' => [
            'expense_account' => 'Select a valid expense account.',
            'organization_limit' => 'No claim limit is configured for your organisation position.',
            'maximum_exceeded' => 'The amount exceeds your organisation limit of MYR :maximum.',
        ],
    ],
    'management' => [
        'title' => 'Claim Management',
        'subtitle' => 'Approve or reject employee claim submissions.',
        'edit_title' => 'Edit Claim Application #:reference',
        'reject_title' => 'Reject Claim Request',
        'rejection_reason' => 'Rejection Reason',
        'show_title' => 'Claim Details',
        'applied_on' => 'Submitted On',
        'approve_title' => 'Approve Claim',
        'decided_by' => 'Decided By',
        'batch_reject_title' => 'Batch Reject Claims',
        'batch_rejection_reason' => 'Rejection Reason (applies to all selected claims)',
    ],
    'filters' => [
        'search_placeholder' => 'Search reference, receipt number, or employee',
        'all_types' => 'All Types',
        'all_statuses' => 'All Statuses',
    ],
    'actions' => [
        'approve' => 'Approve',
        'reject' => 'Reject',
        'batch_approve' => 'Batch Approve',
        'batch_reject' => 'Batch Reject',
        'reject_selected' => 'Reject Selected',
        'pay' => 'Pay Claim',
        'view_payment_voucher' => 'View Payment Voucher',
    ],
    'confirm' => [
        'cancel_claim' => 'Are you sure you want to cancel this claim?',
        'approve_selected' => 'Approve the selected claims?',
        'reject_selected' => 'Reject the selected claims?',
        'batch_title' => 'Confirm Batch Action',
        'proceed' => 'Yes, Proceed',
        'approve_title' => 'Approve Claim',
        'approve_claim' => 'Approve this claim request?',
        'approve_confirm' => 'Yes, Approve',
    ],
    'messages' => [
        'no_records' => 'No claim records found.',
        'no_selection_title' => 'No Selection',
        'no_selection' => 'Select at least one claim to process.',
        'required_title' => 'Required',
        'rejection_reason_required' => 'Provide a rejection reason.',
        'approved' => 'Claim approved successfully.',
        'rejected' => 'Claim rejected successfully.',
        'batch_approved' => 'The selected claims were approved successfully.',
        'batch_rejected' => 'The selected claims were rejected successfully.',
    ],
    'notifications' => [
        'approved_title' => 'Claim Approved',
        'approved_message' => 'Your claim :reference has been approved.',
        'rejected_title' => 'Claim Rejected',
        'rejected_message' => 'Your claim :reference was rejected. Reason: :reason',
    ],
];
