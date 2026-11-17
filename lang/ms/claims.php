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
    'title' => 'Tuntutan',
    'subtitle' => 'Hantar dan pantau tuntutan perbelanjaan anda.',
    'edit_title' => 'Sunting Tuntutan',
    'pending_request' => 'Permohonan Belum Selesai',
    'currency' => 'MYR',
    'table' => [
        'column_reference_number' => 'Nombor Rujukan',
        'column_employee' => 'Pekerja',
        'column_claim_type' => 'Jenis Tuntutan',
        'column_receipt_date' => 'Tarikh Resit',
        'column_receipt_no' => 'Nombor Resit',
        'column_amount' => 'Amaun',
        'column_project' => 'Projek',
        'column_vehicle' => 'Kenderaan',
        'column_rate' => 'Kadar',
        'column_miles' => 'Batu',
        'column_claimant' => 'Penuntut',
        'column_appointed' => 'Pegawai Dilantik',
        'column_clinic' => 'Klinik',
        'column_customer_name' => 'Nama Pelanggan',
        'column_customer_phone' => 'Telefon Pelanggan',
        'column_customer_email' => 'E-mel Pelanggan',
        'column_description' => 'Penerangan',
        'column_attachment' => 'Lampiran',
        'column_status' => 'Status',
        'column_approved_at' => 'Diluluskan Pada',
        'column_rejection_reason' => 'Sebab Penolakan',
    ],
    'types' => [
        'normal' => 'Tuntutan Biasa',
        'project' => 'Tuntutan Projek',
        'vehicle' => 'Tuntutan Kenderaan',
        'clinic' => 'Tuntutan Perubatan',
        'customer' => 'Tuntutan Pelanggan',
    ],
    'status' => [
        'draft' => 'Draf',
        'submitted' => 'Dihantar',
        'pending' => 'Belum Selesai',
        'approved' => 'Diluluskan',
        'paid' => 'Dibayar',
        'posted' => 'Diposting',
        'rejected' => 'Ditolak',
    ],
    'wizard' => [
        'claim_details' => 'Butiran Tuntutan',
        'additional_info' => 'Maklumat Tambahan',
        'step_1_details' => 'Langkah 1: Butiran Asas Tuntutan',
        'step_2_details' => 'Langkah 2: Maklumat Tambahan',
        'current_file' => 'Fail Semasa',
        'view_attachment' => 'Lihat Lampiran',
        'accepted_formats' => 'Format diterima: PDF, JPG, JPEG, PNG. Saiz maksimum: 5 MB.',
        'next_step' => 'Langkah Seterusnya',
        'previous_step' => 'Langkah Sebelumnya',
        'client_company' => 'Syarikat Pelanggan',
        'select_company' => 'Pilih Syarikat',
        'contact_person' => 'Pegawai Perhubungan',
        'select_contact' => 'Pilih Pegawai Perhubungan',
        'save_draft' => 'Simpan sebagai Draf',
        'update_submit' => 'Kemas Kini dan Hantar',
    ],
    'create' => [
        'submit_new_claim' => 'Hantar Tuntutan Baharu',
        'step' => 'Langkah',
        'of' => 'daripada',
        'step_1_category' => 'Langkah 1: Pilih Kategori Tuntutan',
        'select_claim_type' => 'Pilih Jenis Tuntutan',
        'step_2_financial' => 'Langkah 2: Butiran Kewangan',
        'transaction_total' => 'Jumlah Transaksi',
        'transaction_tax' => 'Cukai Transaksi',
        'standard_rate' => 'Kadar Standard',
        'zero_rate' => 'Kadar Sifar',
        'transaction_net' => 'Transaksi Bersih',
        'override_exchange_rate' => 'Gantikan Kadar Pertukaran',
        'base_total_amount' => 'Jumlah Mata Wang Asas',
        'step_3_expense' => 'Langkah 3: Maklumat Perbelanjaan',
        'optional' => 'Pilihan',
        'cancel' => 'Batal',
        'back' => 'Kembali',
        'continue' => 'Teruskan',
        'submit_claim' => 'Hantar Tuntutan',
        'validation_errors' => 'Sila betulkan ralat berikut:',
        'validation_warning_title' => 'Sila semak tuntutan anda',
        'maximum_amount' => 'Maksimum dibenarkan: MYR :amount.',
        'unlimited_amount' => 'Tiada amaun maksimum untuk jenis tuntutan ini.',
        'limit_not_configured' => 'Tiada had tuntutan ditetapkan untuk jawatan organisasi anda.',
    ],
    'placeholders' => [
        'vehicle' => 'Contoh: ABC 1234',
        'patient_name' => 'Nama pesakit',
        'doctor' => 'Doktor atau pakar',
        'clinic' => 'Klinik atau hospital',
        'customer_name' => 'Nama pelanggan',
        'phone' => 'Nombor telefon',
        'email' => 'pelanggan@contoh.com',
        'project' => 'Masukkan butiran projek',
        'description' => 'Masukkan tujuan atau butiran tuntutan',
    ],
    'settings' => [
        'title' => 'Tetapan Tuntutan',
        'subtitle' => 'Tetapkan akaun perbelanjaan dan had organisasi bagi setiap jenis tuntutan.',
        'edit_title' => 'Sunting Tetapan Tuntutan',
        'unlimited' => 'Tiada Had',
        'table' => [
            'column_claim_type' => 'Jenis Tuntutan',
            'column_expense_account' => 'Akaun Perbelanjaan',
            'column_max_amount' => 'Amaun Maksimum Tuntutan',
            'column_organization' => 'Jawatan Organisasi',
            'column_organization_limits' => 'Had Organisasi',
        ],
        'form' => [
            'expense_account' => 'Akaun Perbelanjaan',
            'max_amount' => 'Amaun Maksimum Tuntutan',
            'max_amount_hint' => 'Biarkan kosong jika jenis tuntutan ini tidak mempunyai amaun maksimum.',
            'organization_limits' => 'Had Tuntutan Organisasi',
            'organization_limits_hint' => 'Tetapkan amaun maksimum berbeza bagi setiap jawatan organisasi. Biarkan kosong untuk tiada had.',
        ],
        'validation' => [
            'expense_account' => 'Pilih akaun perbelanjaan yang sah.',
            'organization_limit' => 'Tiada had tuntutan ditetapkan untuk jawatan organisasi anda.',
            'maximum_exceeded' => 'Amaun melebihi had organisasi anda sebanyak MYR :maximum.',
        ],
    ],
    'management' => [
        'title' => 'Pengurusan Tuntutan',
        'subtitle' => 'Luluskan atau tolak penghantaran tuntutan pekerja.',
        'edit_title' => 'Sunting Permohonan Tuntutan #:reference',
        'reject_title' => 'Tolak Permohonan Tuntutan',
        'rejection_reason' => 'Sebab Penolakan',
        'show_title' => 'Butiran Tuntutan',
        'applied_on' => 'Dihantar Pada',
        'approve_title' => 'Luluskan Tuntutan',
        'decided_by' => 'Diputuskan Oleh',
        'batch_reject_title' => 'Tolak Tuntutan Secara Kelompok',
        'batch_rejection_reason' => 'Sebab Penolakan (digunakan untuk semua tuntutan yang dipilih)',
    ],
    'filters' => [
        'search_placeholder' => 'Cari rujukan, nombor resit atau pekerja',
        'all_types' => 'Semua Jenis',
        'all_statuses' => 'Semua Status',
    ],
    'actions' => [
        'approve' => 'Luluskan',
        'reject' => 'Tolak',
        'batch_approve' => 'Luluskan Secara Kelompok',
        'batch_reject' => 'Tolak Secara Kelompok',
        'reject_selected' => 'Tolak yang Dipilih',
        'pay' => 'Bayar Tuntutan',
        'view_payment_voucher' => 'Lihat Baucar Bayaran',
    ],
    'confirm' => [
        'cancel_claim' => 'Adakah anda pasti mahu membatalkan tuntutan ini?',
        'approve_selected' => 'Luluskan tuntutan yang dipilih?',
        'reject_selected' => 'Tolak tuntutan yang dipilih?',
        'batch_title' => 'Sahkan Tindakan Kelompok',
        'proceed' => 'Ya, Teruskan',
        'approve_title' => 'Luluskan Tuntutan',
        'approve_claim' => 'Luluskan permohonan tuntutan ini?',
        'approve_confirm' => 'Ya, Luluskan',
    ],
    'messages' => [
        'no_records' => 'Tiada rekod tuntutan ditemui.',
        'no_selection_title' => 'Tiada Pilihan',
        'no_selection' => 'Pilih sekurang-kurangnya satu tuntutan untuk diproses.',
        'required_title' => 'Diperlukan',
        'rejection_reason_required' => 'Sila berikan sebab penolakan.',
        'approved' => 'Tuntutan berjaya diluluskan.',
        'rejected' => 'Tuntutan berjaya ditolak.',
        'batch_approved' => 'Tuntutan yang dipilih berjaya diluluskan.',
        'batch_rejected' => 'Tuntutan yang dipilih berjaya ditolak.',
    ],
    'notifications' => [
        'approved_title' => 'Tuntutan Diluluskan',
        'approved_message' => 'Tuntutan :reference anda telah diluluskan.',
        'rejected_title' => 'Tuntutan Ditolak',
        'rejected_message' => 'Tuntutan :reference anda telah ditolak. Sebab: :reason',
    ],
];
