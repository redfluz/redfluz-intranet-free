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
    'title' => 'Mesej Dalaman',
    'subtitle' => 'Urus mesej sistem dan log komunikasi dalaman.',
    'conversation' => [
        'title' => 'Mesej',
    ],
    'actions' => [
        'new_message' => 'Mesej Baharu',
        'delete_conversation' => 'Padam Perbualan',
        'download_file' => 'Muat Turun Fail',
        'close' => 'Tutup',
        'cancel' => 'Batal',
        'start_message' => 'Mulakan Mesej',
    ],
    'labels' => [
        'user' => 'Pengguna',
        'you' => 'Anda',
        'attachment' => '[Lampiran]',
        'select_person' => 'Pilih Seseorang:',
    ],
    'placeholders' => [
        'type_message' => 'Taip mesej...',
        'choose_person' => 'Pilih seseorang...',
    ],
    'modal' => [
        'new_message_title' => 'Mulakan Mesej Baharu',
    ],
    'empty' => [
        'no_conversations' => 'Belum ada perbualan aktif.',
        'new_message_hint' => 'Pilih Mesej Baharu untuk memilih seseorang.',
        'your_messages' => 'Mesej Anda',
        'select_contact' => 'Pilih kenalan di sebelah kiri atau pilih Mesej Baharu untuk mula menghantar mesej.',
    ],
    'confirm' => [
        'delete_conversation' => 'Padam perbualan dengan :name?',
        'delete_contact' => 'Buang :name dan seluruh perbualan? Tindakan ini tidak boleh dibatalkan.',
        'delete_current_conversation' => 'Adakah anda pasti mahu memadamkan seluruh perbualan ini? Tindakan ini tidak boleh dibuat asal.',
        'delete_message' => 'Adakah anda pasti mahu memadamkan mesej ini?',
    ],
    'errors' => [
        'file_too_large' => 'Saiz fail melebihi had 10 MB.',
    ],
    'table' => [
        'column_id' => 'ID',
        'column_sender_id' => 'Pengirim',
        'column_receiver_id' => 'Penerima',
        'column_subject' => 'Subjek',
        'column_body' => 'Kandungan Mesej',
        'column_is_read' => 'Status Dibaca',
        'column_read_at' => 'Dibaca Pada',
        'column_created_at' => 'Dihantar Pada',
        'column_updated_at' => 'Dikemas Kini Pada',
    ],
];
