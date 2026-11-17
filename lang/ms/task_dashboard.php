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
    'title' => 'Papan Pemuka Tugasan',
    'subtitle' => 'Pantau tarikh akhir, beban kerja, dan kemajuan seluruh aliran tugasan.',
    'eyebrow' => 'Operasi / Risikan tugasan',

    'actions' => [
        'open_board' => 'Buka Papan Tugasan',
        'new_task' => 'Tugasan Baharu',
        'edit_task' => 'Sunting Tugasan',
        'view_all' => 'Lihat Semua',
    ],
    'summary' => [
        'open' => 'Tugasan Terbuka',
        'open_note' => 'Dalam semua peringkat aktif',
        'overdue' => 'Tertunggak',
        'overdue_note' => 'Melepasi tarikh akhir',
        'due_today' => 'Perlu Hari Ini',
        'due_today_note' => 'Memerlukan perhatian hari ini',
        'completed' => 'Ditutup Bulan Ini',
        'completed_note' => 'Keputusan berjaya dan gagal',
    ],
    'sections' => [
        'throughput' => 'Aliran Kerja',
        'throughput_note' => 'Tugasan diwujudkan berbanding ditutup dalam enam bulan lalu.',
        'workload' => 'Beban Kerja',
        'workload_note' => 'Tugasan terbuka mengikut penerima.',
        'pipeline' => 'Gambaran Aliran',
        'pipeline_note' => 'Jumlah semasa tugasan pada setiap peringkat.',
        'priority' => 'Tugasan Terbuka Mengikut Keutamaan',
        'priority_note' => 'Taburan keutamaan untuk kerja aktif.',
        'attention' => 'Perlu Perhatian',
        'attention_note' => 'Tugasan tertunggak dan perlu disiapkan dalam tujuh hari.',
        'my_tasks' => 'Tugasan Terbuka Saya',
        'my_tasks_note' => 'Tugasan aktif yang diberikan kepada anda.',
        'recent_closed' => 'Baru Ditutup',
        'recent_closed_note' => 'Keputusan berjaya dan gagal terkini.',
    ],
    'labels' => [
        'created' => 'Diwujudkan',
        'closed' => 'Ditutup',
        'last_6_months' => '6 bulan lalu',
        'no_workload' => 'Tiada tugasan terbuka untuk dibahagikan.',
        'unassigned' => 'Belum ditugaskan',
        'no_company' => 'Tiada syarikat',
        'no_due_date' => 'Tiada tarikh akhir',
        'due_today' => 'Perlu hari ini',
        'overdue_by' => 'Tertunggak :days hari',
        'due_in' => 'Perlu dalam :days hari',
        'closed_on' => 'Ditutup :date',
        'tasks' => ':count tugasan',
    ],
    'empty' => [
        'attention' => 'Tiada tugasan tertunggak atau akan datang yang memerlukan perhatian.',
        'my_tasks' => 'Tiada tugasan terbuka diberikan kepada anda.',
        'recent_closed' => 'Belum ada tugasan yang ditutup.',
    ],
];
