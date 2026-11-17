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
    'title' => 'Papan Tugasan',
    'rma_board_title' => 'Tugasan RMA',
    'maintenance_board_title' => 'Tugasan Penyelenggaraan',
    'rma_history' => 'Sejarah Tugasan RMA',
    'rma_history_description' => 'Tugasan RMA dan pesanan jualan yang berjaya dan gagal.',
    'maintenance_history' => 'Sejarah Tugasan Penyelenggaraan',
    'maintenance_history_description' => 'Tugasan penyelenggaraan aset tetap yang berjaya dan gagal.',
    'completed_task_locked' => 'Tugasan ini telah selesai dan bersifat muktamad. Perubahan tidak dibenarkan; buka tugasan baharu untuk kerja seterusnya.',
    'add_task' => 'Tugasan Baharu',
    'delete_title' => 'Padam Tugasan',
    'delete_confirm' => 'Tugasan ini akan dipadam bersama nota dan lampirannya. Tindakan ini tidak boleh dibatalkan.',
    'general' => 'Am',
    'related' => 'Berkaitan',
    'search_placeholder' => 'Cari tajuk atau rujukan...',
    'all_priorities' => 'Semua Keutamaan',
    'all_assignees' => 'Semua Penerima Tugasan',
    'all_sources' => 'Semua Sumber',
    'open_source' => 'Buka Sumber',
    'source_types' => [
        'fixed_asset_maintenance' => 'Penyelenggaraan',
        'rma_estimate' => 'RMA',
        'sales_order' => 'Pesanan Jualan',
        'other' => 'Lain-lain / Tiada Pautan',
    ],
    'no_invoice' => '-- Tidak dipautkan ke invois --',
    'invoice_binding_info' => 'Pilihan. Pautkan tugasan ini ke invois sedia ada untuk menjejaki terhadap urus niaga tertentu.',
    'stage_update_failed' => 'Gagal mengemas kini peringkat tugasan',
    'stage_updated' => 'Peringkat tugasan berjaya dikemas kini.',
    'attachments' => 'Lampiran',
    'view_task' => 'Lihat Tugasan',
    'task_details' => 'Butiran Tugasan',
    'related_rma' => 'RMA Berkaitan',
    'created_by' => 'Dicipta Oleh',
    'completed_at' => 'Diselesaikan Pada',
    'mark_success' => 'Tandakan Berjaya',
    'mark_failure' => 'Tidak Dapat Diselesaikan',
    'task_completed' => 'Tugasan berjaya diselesaikan.',
    'instructions' => 'Arahan Tugasan',
    'no_instructions' => 'Tiada arahan tugasan direkodkan.',
    'work_notes' => 'Nota Kerja',
    'no_work_notes' => 'Tiada kemas kini kerja direkodkan.',
    'add_work_note' => 'Tambah Kemas Kini Kerja',
    'work_note' => 'Butiran Kemas Kini',
    'work_note_added' => 'Kemas kini kerja berjaya direkodkan.',
    'work_note_placeholder' => 'Contoh: Pemeriksaan selesai. SSD gantian diperlukan sebelum pembaikan boleh diteruskan.',
    'save_work_note' => 'Rekod Kemas Kini',
    'note_type_label' => 'Jenis Kemas Kini',
    'completion_note' => 'Hasil Penyelesaian',
    'completion_note_placeholder' => 'Rekod kerja yang telah selesai, atau sebab tugasan tidak dapat diselesaikan.',
    'completion_note_help' => 'Diperlukan apabila menukar tugasan aktif kepada Berjaya atau Gagal.',
    'note_type' => [
        'progress' => 'Kemas Kini Kemajuan',
        'parts_required' => 'Alat Ganti Diperlukan',
        'completion' => 'Selesai',
        'failure' => 'Tidak Dapat Diselesaikan',
    ],
    'assignment_managed_by_rma' => 'Penugasan dikawal melalui aliran kerja RMA yang dipautkan.',
    'no_notes' => 'Tiada nota direkodkan.',
    'no_attachments' => 'Tiada lampiran dimuat naik.',
    'history' => 'Sejarah Tugasan',
    'history_description' => 'Tugasan berjaya dan gagal.',
    'active_tasks' => 'Tugasan Aktif',
    'all_completion_statuses' => 'Semua Status Penyelesaian',
    'completed_date' => 'Tarikh Selesai',
    'history_empty' => 'Tiada tugasan selesai ditemui dalam sejarah.',
    'completed_retention_notice' => 'Tugasan berjaya dan gagal kekal di papan ini selama 30 hari. Tugasan yang selesai juga disenaraikan dalam Sejarah Tugasan.',
    'stage' => [
        'introduction' => 'Pengenalan',
        'follow_up_1' => 'Susulan 1',
        'follow_up_2' => 'Susulan 2',
        'proceed' => 'Teruskan',
        'success' => 'Berjaya',
        'failure' => 'Gagal',
    ],

    'priority' => [
        'low' => 'Rendah',
        'medium' => 'Sederhana',
        'high' => 'Tinggi',
    ],

    'table' => [
        'column_reference_number' => 'Rujukan',
        'column_title' => 'Tajuk',
        'column_company' => 'Syarikat',
        'column_contact' => 'Kenalan',
        'column_invoice' => 'Invois Dipautkan',
        'column_stage' => 'Peringkat',
        'column_priority' => 'Keutamaan',
        'column_due_date' => 'Tarikh Akhir',
        'column_assigned_to' => 'Ditugaskan Kepada',
        'column_notes' => 'Nota',
        'column_actions' => 'Tindakan',
    ],
];
