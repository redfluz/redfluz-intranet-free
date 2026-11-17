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
    'title' => 'Papan Tugas',
    'rma_board_title' => 'Tugas RMA',
    'maintenance_board_title' => 'Tugas Perawatan',
    'rma_history' => 'Riwayat Tugas RMA',
    'rma_history_description' => 'Tugas RMA dan pesanan penjualan yang berhasil dan gagal.',
    'maintenance_history' => 'Riwayat Tugas Perawatan',
    'maintenance_history_description' => 'Tugas perawatan aset tetap yang berhasil dan gagal.',
    'completed_task_locked' => 'Tugas ini sudah selesai dan bersifat final. Perubahan tidak diizinkan; buka tugas baru untuk pekerjaan berikutnya.',
    'add_task' => 'Tugas Baru',
    'delete_title' => 'Hapus Tugas',
    'delete_confirm' => 'Tugas ini akan dihapus beserta catatan dan lampirannya. Tindakan ini tidak dapat dibatalkan.',
    'general' => 'Umum',
    'related' => 'Terkait',
    'search_placeholder' => 'Cari judul atau referensi...',
    'all_priorities' => 'Semua Prioritas',
    'all_assignees' => 'Semua Penerima Tugas',
    'all_sources' => 'Semua Sumber',
    'open_source' => 'Buka Sumber',
    'source_types' => [
        'fixed_asset_maintenance' => 'Pemeliharaan',
        'rma_estimate' => 'RMA',
        'sales_order' => 'Pesanan Penjualan',
        'other' => 'Lainnya / Tidak Tertaut',
    ],
    'no_invoice' => '-- Tidak tertaut ke faktur --',
    'invoice_binding_info' => 'Opsional. Tautkan tugas ini ke faktur yang ada untuk melacak terhadap kesepakatan tertentu.',
    'stage_update_failed' => 'Tidak dapat memperbarui tahap tugas',
    'stage_updated' => 'Tahap tugas berhasil diperbarui.',
    'attachments' => 'Lampiran',
    'view_task' => 'Lihat Tugas',
    'task_details' => 'Detail Tugas',
    'related_rma' => 'RMA Terkait',
    'created_by' => 'Dibuat Oleh',
    'completed_at' => 'Diselesaikan Pada',
    'mark_success' => 'Tandai Berhasil',
    'mark_failure' => 'Tidak Dapat Diselesaikan',
    'task_completed' => 'Tugas berhasil diselesaikan.',
    'instructions' => 'Instruksi Tugas',
    'no_instructions' => 'Tidak ada instruksi tugas yang dicatat.',
    'work_notes' => 'Catatan Kerja',
    'no_work_notes' => 'Belum ada pembaruan kerja yang dicatat.',
    'add_work_note' => 'Tambah Pembaruan Kerja',
    'work_note' => 'Detail Pembaruan',
    'work_note_added' => 'Pembaruan kerja berhasil dicatat.',
    'work_note_placeholder' => 'Contoh: Inspeksi selesai. SSD pengganti diperlukan sebelum perbaikan dapat dilanjutkan.',
    'save_work_note' => 'Catat Pembaruan',
    'note_type_label' => 'Jenis Pembaruan',
    'completion_note' => 'Hasil Penyelesaian',
    'completion_note_placeholder' => 'Catat apa yang telah diselesaikan, atau jelaskan mengapa tugas tidak dapat diselesaikan.',
    'completion_note_help' => 'Wajib diisi saat mengubah tugas aktif ke Berhasil atau Gagal.',
    'note_type' => [
        'progress' => 'Pembaruan Progres',
        'parts_required' => 'Bagian Dibutuhkan',
        'completion' => 'Selesai',
        'failure' => 'Tidak Dapat Diselesaikan',
    ],
    'assignment_managed_by_rma' => 'Penugasan dikendalikan dari alur kerja RMA terkait.',
    'no_notes' => 'Tidak ada catatan.',
    'no_attachments' => 'Tidak ada lampiran diunggah.',
    'history' => 'Riwayat Tugas',
    'history_description' => 'Tugas berhasil dan gagal.',
    'active_tasks' => 'Tugas Aktif',
    'all_completion_statuses' => 'Semua Status Penyelesaian',
    'completed_date' => 'Tanggal Selesai',
    'history_empty' => 'Tidak ada tugas selesai ditemukan dalam riwayat.',
    'completed_retention_notice' => 'Tugas berhasil dan gagal tetap berada di papan ini selama 30 hari. Tugas yang selesai juga tercantum di Riwayat Tugas.',
    'stage' => [
        'introduction' => 'Pendahuluan',
        'follow_up_1' => 'Tindak Lanjut 1',
        'follow_up_2' => 'Tindak Lanjut 2',
        'proceed' => 'Lanjutkan',
        'success' => 'Berhasil',
        'failure' => 'Gagal',
    ],

    'priority' => [
        'low' => 'Rendah',
        'medium' => 'Sedang',
        'high' => 'Tinggi',
    ],

    'table' => [
        'column_reference_number' => 'Referensi',
        'column_title' => 'Judul',
        'column_company' => 'Perusahaan',
        'column_contact' => 'Kontak',
        'column_invoice' => 'Faktur Tertaut',
        'column_stage' => 'Tahap',
        'column_priority' => 'Prioritas',
        'column_due_date' => 'Tanggal Jatuh Tempo',
        'column_assigned_to' => 'Ditugaskan Kepada',
        'column_notes' => 'Catatan',
        'column_actions' => 'Tindakan',
    ],
];
