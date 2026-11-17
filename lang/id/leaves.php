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
    'general' => 'Umum',
    'employee' => 'Karyawan',
    'approval' => 'Persetujuan',
    'employee_email' => 'Email',
    'employee_role' => 'Peran',

    'dashboard' => [
        'title' => 'Dasbor cuti',
        'subtitle' => 'Kelola pengajuan cuti Anda.',
        'apply_button' => 'Ajukan Cuti',
        'submit_button' => 'Kirim Pengajuan',
        'cancel_button' => 'Batal',
        'details_button' => 'Detail',
        'pending_requests' => 'Pengajuan Tertunda',
        'recent_requests' => 'Pengajuan Cuti Terbaru',
        'entitlements' => 'Hak Cuti',
        'on_leave_this_week' => 'Cuti Minggu Ini',
        'no_requests' => 'Belum ada pengajuan cuti.',
        'no_one_on_leave' => 'Tidak ada orang lain yang cuti minggu ini.',
        'select_type' => 'Pilih jenis cuti',
        'from_date' => 'Dari Tanggal',
        'to_date' => 'Sampai Tanggal',
        'reason_placeholder' => 'Alasan singkat...',
        'confirm_cancel' => 'Batalkan pengajuan cuti ini?',
        'days_suffix' => 'Hari',
        'remaining_suffix' => 'Sisa',
        'balance_option' => ':remaining hari tersisa',
        'balance_details' => 'Saldo tersedia: :remaining dari :entitled hari',
        'balance_warning_title' => 'Saldo cuti terlampaui',
        'balance_warning_message' => 'Pengajuan ini menggunakan :requested hari, tetapi hanya tersisa :remaining hari untuk :type.',
        'all_holiday_error' => 'Tanggal yang dipilih jatuh seluruhnya pada akhir pekan/hari libur — tidak ada hari kerja untuk mengambil cuti.',
        'insufficient_balance_error' => 'Saldo :type tidak mencukupi — hanya tersisa :remaining hari tahun ini.',
    ],

    'admin' => [
        'title' => 'Pengajuan Cuti',
        'search_placeholder' => 'Cari referensi atau karyawan...',
        'all_types' => 'Semua Jenis',
        'all_status' => 'Semua Status',
        'no_results' => 'Tidak ada pengajuan cuti yang ditemukan.',
        'approve' => 'Setujui',
        'reject' => 'Tolak',
        'reject_title' => 'Tolak Pengajuan Cuti',
        'rejection_reason' => 'Alasan Penolakan',
        'confirm_approve' => 'Setujui pengajuan cuti ini?',
        'show_title' => 'Detail Pengajuan Cuti',
        'applied_on' => 'Diajukan Pada',
        'approve_title' => 'Setujui Pengajuan Cuti',
        'decided_by' => 'Diputuskan Oleh',
        'attachment' => 'Lampiran',
        'batch_approved' => 'Pengajuan cuti terpilih telah disetujui.',
        'batch_rejected' => 'Pengajuan cuti terpilih telah ditolak.',
        'batch_approve' => 'Setujui yang Dipilih',
        'batch_reject' => 'Tolak yang Dipilih',
        'batch_reject_title' => 'Tolak Pengajuan Cuti yang Dipilih',
        'batch_rejection_reason' => 'Alasan penolakan',
        'no_selection_title' => 'Tidak ada pengajuan yang dipilih',
        'no_selection' => 'Pilih setidaknya satu pengajuan yang tertunda.',
        'required_title' => 'Alasan wajib diisi',
        'rejection_reason_required' => 'Masukkan alasan penolakan sebelum melanjutkan.',
        'confirm_batch_title' => 'Konfirmasi aksi',
        'confirm_approve_selected' => 'Setujui pengajuan cuti yang dipilih?',
        'confirm_reject_selected' => 'Tolak pengajuan cuti yang dipilih?',
        'confirm_proceed' => 'Lanjutkan',
        'decided_by' => 'Diputuskan Oleh',
    ],

    'notifications' => [
        'approved_title' => 'Pengajuan cuti disetujui',
        'approved_message' => 'Pengajuan cuti :reference telah disetujui.',
        'rejected_title' => 'Pengajuan cuti ditolak',
        'rejected_message' => 'Pengajuan cuti :reference telah ditolak. Alasan: :reason',
    ],

    'settings' => [
        'title' => 'Pengaturan Cuti',
        'description' => 'Tetapkan jumlah hari cuti default yang menjadi hak setiap karyawan per tahun kalender untuk setiap jenis cuti.',
        'days_per_annum' => 'hari / tahun',
        'overrides_title' => 'Penyesuaian per Karyawan',
        'overrides_description' => 'Berikan hak cuti yang berbeda dari default kepada karyawan tertentu (mis. cuti tahunan tambahan untuk staf senior).',
        'no_overrides' => 'Tidak ada penyesuaian yang dikonfigurasi — semua menggunakan default.',
    ],

    'calendar' => [
        'title' => 'Kalender Cuti',
        'subtitle' => 'Lihat cuti yang disetujui untuk semua orang di seluruh organisasi.',
        'previous_month' => 'Bulan sebelumnya',
        'next_month' => 'Bulan berikutnya',
        'month' => 'Bulan',
        'employee' => 'Karyawan',
        'department' => 'Departemen',
        'leave_type' => 'Jenis cuti',
        'leave_dates' => 'Tanggal cuti',
        'schedule_list' => 'Daftar Cuti Karyawan',
        'no_leave' => 'Tidak ada cuti yang disetujui untuk bulan ini.',
        'holiday_legend' => 'Hari Libur',
        'on_leave_legend' => 'Cuti',
        'more' => 'lainnya',
    ],

    'type' => [
        'annual' => 'Cuti Tahunan',
        'sick' => 'Cuti Sakit',
        'casual' => 'Cuti Biasa',
        'hospitalisation' => 'Cuti Rawat Inap',
        'maternity' => 'Cuti Melahirkan',
        'paternity' => 'Cuti Ayah',
        'compassionate' => 'Cuti Berduka',
        'marriage' => 'Cuti Menikah',
        'unpaid' => 'Cuti Tanpa Gaji',
    ],

    'status' => [
        'draft' => 'Draf',
        'submitted' => 'Diajukan',
        'pending' => 'Tertunda',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
    ],

    'validation' => [
        'payroll_locked' => 'Periode penggajian :month sudah diproses atau diposting. Perubahan cuti untuk periode ini dikunci.',
    ],

    'table' => [
        'column_reference_number' => 'Referensi',
        'column_employee' => 'Karyawan',
        'column_type' => 'Jenis',
        'column_dates' => 'Tanggal',
        'column_start_date' => 'Tanggal Mulai',
        'column_end_date' => 'Tanggal Selesai',
        'column_days' => 'Hari',
        'column_status' => 'Status',
        'column_reason' => 'Alasan',
        'column_action' => 'Aksi',
    ],
];
