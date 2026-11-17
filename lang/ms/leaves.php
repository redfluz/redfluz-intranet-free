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
    'general' => 'Am',
    'employee' => 'Pekerja',
    'approval' => 'Kelulusan',
    'employee_email' => 'Emel',
    'employee_role' => 'Peranan',

    'dashboard' => [
        'title' => 'Papan Pemuka Cuti',
        'subtitle' => 'Uruskan permohonan cuti anda.',
        'apply_button' => 'Mohon Cuti',
        'submit_button' => 'Hantar Permohonan',
        'cancel_button' => 'Batal',
        'details_button' => 'Butiran',
        'pending_requests' => 'Permohonan Menunggu',
        'recent_requests' => 'Permohonan Cuti Terkini',
        'entitlements' => 'Kelayakan Cuti',
        'on_leave_this_week' => 'Bercuti Minggu Ini',
        'no_requests' => 'Belum ada permohonan cuti.',
        'no_one_on_leave' => 'Tiada rakan sekerja lain bercuti minggu ini.',
        'select_type' => 'Pilih jenis cuti',
        'from_date' => 'Tarikh Mula',
        'to_date' => 'Tarikh Tamat',
        'reason_placeholder' => 'Sebab ringkas...',
        'confirm_cancel' => 'Batalkan permohonan cuti ini?',
        'days_suffix' => 'Hari',
        'remaining_suffix' => 'Baki',
        'balance_option' => 'Baki :remaining hari',
        'balance_details' => 'Baki tersedia: :remaining daripada :entitled hari',
        'balance_warning_title' => 'Baki cuti tidak mencukupi',
        'balance_warning_message' => 'Permohonan ini menggunakan :requested hari, tetapi baki :type hanya :remaining hari.',
        'all_holiday_error' => 'Tarikh yang dipilih jatuh sepenuhnya pada hujung minggu/cuti umum — tiada hari bekerja untuk permohonan cuti.',
        'insufficient_balance_error' => 'Baki :type tidak mencukupi — hanya :remaining hari berbaki tahun ini.',
    ],

    'admin' => [
        'title' => 'Permohonan Cuti',
        'search_placeholder' => 'Cari rujukan atau pekerja...',
        'all_types' => 'Semua Jenis',
        'all_status' => 'Semua Status',
        'no_results' => 'Tiada permohonan cuti dijumpai.',
        'approve' => 'Luluskan',
        'reject' => 'Tolak',
        'reject_title' => 'Tolak Permohonan Cuti',
        'rejection_reason' => 'Sebab Penolakan',
        'confirm_approve' => 'Luluskan permohonan cuti ini?',
        'show_title' => 'Butiran Permohonan Cuti',
        'applied_on' => 'Dipohon Pada',
        'approve_title' => 'Luluskan Permohonan Cuti',
        'decided_by' => 'Diputuskan Oleh',
        'attachment' => 'Lampiran',
        'batch_approved' => 'Permohonan cuti yang dipilih telah diluluskan.',
        'batch_rejected' => 'Permohonan cuti yang dipilih telah ditolak.',
        'batch_approve' => 'Luluskan Yang Dipilih',
        'batch_reject' => 'Tolak Yang Dipilih',
        'batch_reject_title' => 'Tolak Permohonan Cuti Yang Dipilih',
        'batch_rejection_reason' => 'Sebab penolakan',
        'no_selection_title' => 'Tiada permohonan dipilih',
        'no_selection' => 'Pilih sekurang-kurangnya satu permohonan yang menunggu.',
        'required_title' => 'Sebab diperlukan',
        'rejection_reason_required' => 'Masukkan sebab penolakan sebelum meneruskan.',
        'confirm_batch_title' => 'Sahkan tindakan',
        'confirm_approve_selected' => 'Luluskan permohonan cuti yang dipilih?',
        'confirm_reject_selected' => 'Tolak permohonan cuti yang dipilih?',
        'confirm_proceed' => 'Teruskan',
        'decided_by' => 'Diputuskan Oleh',
    ],

    'notifications' => [
        'approved_title' => 'Permohonan cuti diluluskan',
        'approved_message' => 'Permohonan cuti :reference telah diluluskan.',
        'rejected_title' => 'Permohonan cuti ditolak',
        'rejected_message' => 'Permohonan cuti :reference telah ditolak. Sebab: :reason',
    ],

    'settings' => [
        'title' => 'Tetapan Cuti',
        'description' => 'Tetapkan bilangan hari Tetapan yang layak diterima setiap pekerja bagi setiap tahun kalendar mengikut jenis cuti.',
        'days_per_annum' => 'hari / tahun',
        'overrides_title' => 'Pengecualian Mengikut Pekerja',
        'overrides_description' => 'Berikan kelayakan berbeza kepada pekerja tertentu berbanding Tetapan (cth. cuti tahunan tambahan untuk kakitangan kanan).',
        'no_overrides' => 'Tiada pengecualian ditetapkan — semua menggunakan tetapan Tetapan.',
    ],

    'calendar' => [
        'title' => 'Kalendar Cuti',
        'subtitle' => 'Lihat cuti yang diluluskan untuk semua warga organisasi.',
        'previous_month' => 'Bulan sebelumnya',
        'next_month' => 'Bulan berikutnya',
        'month' => 'Bulan',
        'employee' => 'Pekerja',
        'department' => 'Jabatan',
        'leave_type' => 'Jenis cuti',
        'leave_dates' => 'Tarikh cuti',
        'schedule_list' => 'Senarai Kakitangan Bercuti',
        'no_leave' => 'Tiada cuti yang diluluskan dijadualkan untuk bulan ini.',
        'holiday_legend' => 'Cuti Umum',
        'on_leave_legend' => 'Bercuti',
        'more' => 'lagi',
    ],

    'type' => [
        'annual' => 'Cuti Tahunan',
        'sick' => 'Cuti Sakit',
        'casual' => 'Cuti Biasa',
        'hospitalisation' => 'Cuti Hospitalisasi',
        'maternity' => 'Cuti Bersalin',
        'paternity' => 'Cuti Paterniti',
        'compassionate' => 'Cuti Ihsan',
        'marriage' => 'Cuti Perkahwinan',
        'unpaid' => 'Cuti Tanpa Gaji',
    ],

    'status' => [
        'draft' => 'Draf',
        'submitted' => 'Dihantar',
        'pending' => 'Menunggu',
        'approved' => 'Diluluskan',
        'rejected' => 'Ditolak',
    ],

    'validation' => [
        'payroll_locked' => 'Tempoh penggajian :month telah diproses atau dipos. Perubahan cuti untuk tempoh ini dikunci.',
    ],

    'table' => [
        'column_reference_number' => 'Rujukan',
        'column_employee' => 'Pekerja',
        'column_type' => 'Jenis',
        'column_dates' => 'Tarikh',
        'column_start_date' => 'Tarikh Mula',
        'column_end_date' => 'Tarikh Tamat',
        'column_days' => 'Hari',
        'column_status' => 'Status',
        'column_reason' => 'Sebab',
        'column_action' => 'Tindakan',
    ],
];
