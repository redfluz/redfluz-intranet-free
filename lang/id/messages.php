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
    'title' => 'Pesan Internal',
    'subtitle' => 'Kelola pesan sistem dan log komunikasi internal.',
    'conversation' => [
        'title' => 'Pesan',
    ],
    'actions' => [
        'new_message' => 'Pesan Baru',
        'delete_conversation' => 'Hapus Percakapan',
        'download_file' => 'Unduh File',
        'close' => 'Tutup',
        'cancel' => 'Batal',
        'start_message' => 'Mulai Pesan',
    ],
    'labels' => [
        'user' => 'Pengguna',
        'you' => 'Anda',
        'attachment' => '[Lampiran]',
        'select_person' => 'Pilih Seseorang:',
    ],
    'placeholders' => [
        'type_message' => 'Ketik pesan...',
        'choose_person' => 'Pilih seseorang...',
    ],
    'modal' => [
        'new_message_title' => 'Mulai Pesan Baru',
    ],
    'empty' => [
        'no_conversations' => 'Belum ada percakapan aktif.',
        'new_message_hint' => 'Pilih Pesan Baru untuk memilih seseorang.',
        'your_messages' => 'Pesan Anda',
        'select_contact' => 'Pilih kontak di sebelah kiri atau pilih Pesan Baru untuk mulai mengirim pesan.',
    ],
    'confirm' => [
        'delete_conversation' => 'Hapus percakapan dengan :name?',
        'delete_contact' => 'Hapus :name dan seluruh percakapan? Tindakan ini tidak dapat dibatalkan.',
        'delete_current_conversation' => 'Anda yakin ingin menghapus seluruh percakapan ini? Tindakan ini tidak dapat dibatalkan.',
        'delete_message' => 'Anda yakin ingin menghapus pesan ini?',
    ],
    'errors' => [
        'file_too_large' => 'Ukuran file melebihi batas 10 MB.',
    ],
    'table' => [
        'column_id' => 'ID',
        'column_sender_id' => 'Pengirim',
        'column_receiver_id' => 'Penerima',
        'column_subject' => 'Subjek',
        'column_body' => 'Isi Pesan',
        'column_is_read' => 'Status Baca',
        'column_read_at' => 'Dibaca Pada',
        'column_created_at' => 'Dikirim Pada',
        'column_updated_at' => 'Diperbarui Pada',
    ],
];
