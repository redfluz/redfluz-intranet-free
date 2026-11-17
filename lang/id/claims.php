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
    'title' => 'Klaim',
    'subtitle' => 'Ajukan dan pantau klaim biaya Anda.',
    'edit_title' => 'Edit Klaim',
    'pending_request' => 'Permintaan Tertunda',
    'currency' => 'MYR',
    'table' => [
        'column_reference_number' => 'Nomor Referensi',
        'column_employee' => 'Karyawan',
        'column_claim_type' => 'Jenis Klaim',
        'column_receipt_date' => 'Tanggal Kuitansi',
        'column_receipt_no' => 'Nomor Kuitansi',
        'column_amount' => 'Jumlah',
        'column_project' => 'Proyek',
        'column_vehicle' => 'Kendaraan',
        'column_rate' => 'Tarif',
        'column_miles' => 'Mil',
        'column_claimant' => 'Pengklaim',
        'column_appointed' => 'Ditunjuk',
        'column_clinic' => 'Klinik',
        'column_customer_name' => 'Nama Pelanggan',
        'column_customer_phone' => 'Telepon Pelanggan',
        'column_customer_email' => 'Email Pelanggan',
        'column_description' => 'Deskripsi',
        'column_attachment' => 'Lampiran',
        'column_status' => 'Status',
        'column_approved_at' => 'Disetujui Pada',
        'column_rejection_reason' => 'Alasan Penolakan',
    ],
    'types' => [
        'normal' => 'Klaim Biasa',
        'project' => 'Klaim Proyek',
        'vehicle' => 'Klaim Kendaraan',
        'clinic' => 'Klaim Kesehatan',
        'customer' => 'Klaim Pelanggan',
    ],
    'status' => [
        'draft' => 'Draf',
        'submitted' => 'Diajukan',
        'pending' => 'Tertunda',
        'approved' => 'Disetujui',
        'paid' => 'Dibayar',
        'posted' => 'Diposting',
        'rejected' => 'Ditolak',
    ],
    'wizard' => [
        'claim_details' => 'Detail Klaim',
        'additional_info' => 'Informasi Tambahan',
        'step_1_details' => 'Langkah 1: Detail Klaim Dasar',
        'step_2_details' => 'Langkah 2: Informasi Tambahan',
        'current_file' => 'Berkas Saat Ini',
        'view_attachment' => 'Lihat Lampiran',
        'accepted_formats' => 'Format yang diterima: PDF, JPG, JPEG, PNG. Ukuran maksimum: 5 MB.',
        'next_step' => 'Langkah Berikutnya',
        'previous_step' => 'Langkah Sebelumnya',
        'client_company' => 'Perusahaan Klien',
        'select_company' => 'Pilih Perusahaan',
        'contact_person' => 'Kontak Person',
        'select_contact' => 'Pilih Kontak',
        'save_draft' => 'Simpan sebagai Draf',
        'update_submit' => 'Perbarui dan Ajukan',
    ],
    'create' => [
        'submit_new_claim' => 'Ajukan Klaim Baru',
        'step' => 'Langkah',
        'of' => 'dari',
        'step_1_category' => 'Langkah 1: Pilih Kategori Klaim',
        'select_claim_type' => 'Pilih Jenis Klaim',
        'step_2_financial' => 'Langkah 2: Detail Keuangan',
        'transaction_total' => 'Total Transaksi',
        'transaction_tax' => 'Pajak Transaksi',
        'standard_rate' => 'Tarif Standar',
        'zero_rate' => 'Tarif Nol',
        'transaction_net' => 'Net Transaksi',
        'override_exchange_rate' => 'Timpa Kurs',
        'base_total_amount' => 'Jumlah Total Dasar',
        'step_3_expense' => 'Langkah 3: Informasi Biaya',
        'optional' => 'Opsional',
        'cancel' => 'Batal',
        'back' => 'Kembali',
        'continue' => 'Lanjutkan',
        'submit_claim' => 'Ajukan Klaim',
        'validation_errors' => 'Perbaiki kesalahan berikut:',
        'validation_warning_title' => 'Silakan tinjau klaim Anda',
        'maximum_amount' => 'Maksimum yang diizinkan: MYR :amount.',
        'unlimited_amount' => 'Tidak ada batas maksimum untuk jenis klaim ini.',
        'limit_not_configured' => 'Belum ada batas klaim yang dikonfigurasi untuk posisi organisasi Anda.',
    ],
    'placeholders' => [
        'vehicle' => 'Misalnya: ABC 1234',
        'patient_name' => 'Nama pasien',
        'doctor' => 'Dokter atau spesialis',
        'clinic' => 'Klinik atau rumah sakit',
        'customer_name' => 'Nama pelanggan',
        'phone' => 'Nomor telepon',
        'email' => 'customer@example.com',
        'project' => 'Masukkan detail proyek',
        'description' => 'Masukkan tujuan atau detail klaim',
    ],
    'settings' => [
        'title' => 'Pengaturan Klaim',
        'subtitle' => 'Konfigurasikan akun biaya dan batas organisasi untuk setiap jenis klaim.',
        'edit_title' => 'Edit Pengaturan Klaim',
        'unlimited' => 'Tanpa Batas',
        'table' => [
            'column_claim_type' => 'Jenis Klaim',
            'column_expense_account' => 'Akun Biaya',
            'column_max_amount' => 'Jumlah Klaim Maksimum',
            'column_organization' => 'Posisi Organisasi',
            'column_organization_limits' => 'Batas Organisasi',
        ],
        'form' => [
            'expense_account' => 'Akun Biaya',
            'max_amount' => 'Jumlah Klaim Maksimum',
            'max_amount_hint' => 'Kosongkan jika jenis klaim ini tidak memiliki jumlah maksimum.',
            'organization_limits' => 'Batas Klaim Organisasi',
            'organization_limits_hint' => 'Tetapkan maksimum berbeda untuk setiap posisi organisasi. Kosongkan jumlah untuk tanpa batas.',
        ],
        'validation' => [
            'expense_account' => 'Pilih akun biaya yang valid.',
            'organization_limit' => 'Belum ada batas klaim yang dikonfigurasi untuk posisi organisasi Anda.',
            'maximum_exceeded' => 'Jumlah melebihi batas organisasi Anda sebesar MYR :maximum.',
        ],
    ],
    'management' => [
        'title' => 'Manajemen Klaim',
        'subtitle' => 'Setujui atau tolak pengajuan klaim karyawan.',
        'edit_title' => 'Edit Aplikasi Klaim #:reference',
        'reject_title' => 'Tolak Permintaan Klaim',
        'rejection_reason' => 'Alasan Penolakan',
        'show_title' => 'Detail Klaim',
        'applied_on' => 'Dikirim Pada',
        'approve_title' => 'Setujui Klaim',
        'decided_by' => 'Diputuskan Oleh',
        'batch_reject_title' => 'Tolak Klaim Secara Massal',
        'batch_rejection_reason' => 'Alasan Penolakan (berlaku untuk semua klaim yang dipilih)',
    ],
    'filters' => [
        'search_placeholder' => 'Cari referensi, nomor kuitansi, atau karyawan',
        'all_types' => 'Semua Jenis',
        'all_statuses' => 'Semua Status',
    ],
    'actions' => [
        'approve' => 'Setujui',
        'reject' => 'Tolak',
        'batch_approve' => 'Setujui Massal',
        'batch_reject' => 'Tolak Massal',
        'reject_selected' => 'Tolak yang Dipilih',
        'pay' => 'Bayar Klaim',
        'view_payment_voucher' => 'Lihat Bukti Pembayaran',
    ],
    'confirm' => [
        'cancel_claim' => 'Apakah Anda yakin ingin membatalkan klaim ini?',
        'approve_selected' => 'Setujui klaim yang dipilih?',
        'reject_selected' => 'Tolak klaim yang dipilih?',
        'batch_title' => 'Konfirmasi Tindakan Massal',
        'proceed' => 'Ya, Lanjutkan',
        'approve_title' => 'Setujui Klaim',
        'approve_claim' => 'Setujui permintaan klaim ini?',
        'approve_confirm' => 'Ya, Setujui',
    ],
    'messages' => [
        'no_records' => 'Tidak ada catatan klaim ditemukan.',
        'no_selection_title' => 'Tidak Ada Pilihan',
        'no_selection' => 'Pilih setidaknya satu klaim untuk diproses.',
        'required_title' => 'Wajib',
        'rejection_reason_required' => 'Berikan alasan penolakan.',
        'approved' => 'Klaim berhasil disetujui.',
        'rejected' => 'Klaim berhasil ditolak.',
        'batch_approved' => 'Klaim yang dipilih berhasil disetujui.',
        'batch_rejected' => 'Klaim yang dipilih berhasil ditolak.',
    ],
    'notifications' => [
        'approved_title' => 'Klaim Disetujui',
        'approved_message' => 'Klaim Anda :reference telah disetujui.',
        'rejected_title' => 'Klaim Ditolak',
        'rejected_message' => 'Klaim Anda :reference ditolak. Alasan: :reason',
    ],
];
