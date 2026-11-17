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
    'title' => 'Dasbor Tugas',
    'subtitle' => 'Pantau tenggat, beban kerja, dan kemajuan di seluruh alur tugas.',
    'eyebrow' => 'Operasional / Intelijen tugas',

    'actions' => [
        'open_board' => 'Buka Papan Tugas',
        'new_task' => 'Tugas Baru',
        'edit_task' => 'Ubah Tugas',
        'view_all' => 'Lihat Semua',
    ],
    'summary' => [
        'open' => 'Tugas Terbuka',
        'open_note' => 'Di seluruh tahap alur aktif',
        'overdue' => 'Terlambat',
        'overdue_note' => 'Melewati tanggal jatuh tempo',
        'due_today' => 'Jatuh Tempo Hari Ini',
        'due_today_note' => 'Perlu perhatian hari ini',
        'completed' => 'Ditutup Bulan Ini',
        'completed_note' => 'Hasil berhasil dan gagal',
    ],
    'sections' => [
        'throughput' => 'Arus Kerja',
        'throughput_note' => 'Tugas dibuat berbanding ditutup dalam enam bulan terakhir.',
        'workload' => 'Beban Kerja',
        'workload_note' => 'Tugas terbuka berdasarkan penerima tugas.',
        'pipeline' => 'Ringkasan Alur',
        'pipeline_note' => 'Jumlah tugas saat ini di setiap tahap.',
        'priority' => 'Tugas Terbuka menurut Prioritas',
        'priority_note' => 'Sebaran prioritas untuk pekerjaan aktif.',
        'attention' => 'Perlu Perhatian',
        'attention_note' => 'Tugas yang terlambat dan tugas yang jatuh tempo dalam tujuh hari.',
        'my_tasks' => 'Tugas Terbuka Saya',
        'my_tasks_note' => 'Tugas aktif yang ditugaskan kepada Anda.',
        'recent_closed' => 'Baru Saja Ditutup',
        'recent_closed_note' => 'Hasil berhasil dan gagal terbaru.',
    ],
    'labels' => [
        'created' => 'Dibuat',
        'closed' => 'Ditutup',
        'last_6_months' => '6 bulan terakhir',
        'no_workload' => 'Tidak ada tugas terbuka untuk dibagikan.',
        'unassigned' => 'Belum Ditugaskan',
        'no_company' => 'Tanpa perusahaan',
        'no_due_date' => 'Tanpa tanggal jatuh tempo',
        'due_today' => 'Jatuh tempo hari ini',
        'overdue_by' => 'Terlambat :days hari|Terlambat :days hari',
        'due_in' => 'Jatuh tempo dalam :days hari|Jatuh tempo dalam :days hari',
        'closed_on' => 'Ditutup pada :date',
        'tasks' => ':count tugas|:count tugas',
    ],
    'empty' => [
        'attention' => 'Tidak ada tugas terlambat atau mendatang yang perlu perhatian.',
        'my_tasks' => 'Tidak ada tugas terbuka yang ditugaskan kepada Anda.',
        'recent_closed' => 'Belum ada tugas yang ditutup.',
    ],
];
