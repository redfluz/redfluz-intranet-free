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
    'title' => 'Internal Messages',
    'subtitle' => 'Manage system messages and internal communication logs.',
    'conversation' => [
        'title' => 'Messages',
    ],
    'actions' => [
        'new_message' => 'New Message',
        'delete_conversation' => 'Delete Conversation',
        'download_file' => 'Download File',
        'reply' => 'Reply',
        'delete' => 'Delete',
        'delete_for_me' => 'Delete for me',
        'delete_for_everyone' => 'Delete for everyone',
        'close' => 'Close',
        'cancel' => 'Cancel',
        'start_message' => 'Start Message',
    ],
    'labels' => [
        'user' => 'User',
        'you' => 'You',
        'attachment' => '[Attachment]',
        'select_person' => 'Select a Person:',
    ],
    'placeholders' => [
        'type_message' => 'Type a message...',
        'choose_person' => 'Choose someone...',
    ],
    'modal' => [
        'new_message_title' => 'Start a New Message',
    ],
    'empty' => [
        'no_conversations' => 'No active conversations yet.',
        'new_message_hint' => 'Select New Message to choose a person.',
        'your_messages' => 'Your Messages',
        'select_contact' => 'Select a contact from the left or select New Message to start messaging.',
    ],
    'confirm' => [
        'delete_conversation' => 'Delete the conversation with :name?',
        'delete_contact' => 'Remove :name and the entire conversation? This cannot be undone.',
        'delete_current_conversation' => 'Are you sure you want to delete this entire conversation? This action cannot be undone.',
        'delete_message' => 'Are you sure you want to delete this message?',
    ],
    'errors' => [
        'file_too_large' => 'The file size exceeds the 10 MB limit.',
    ],
    'table' => [
        'column_id' => 'ID',
        'column_sender_id' => 'Sender',
        'column_receiver_id' => 'Recipient',
        'column_subject' => 'Subject',
        'column_body' => 'Message Content',
        'column_is_read' => 'Read Status',
        'column_read_at' => 'Read At',
        'column_created_at' => 'Sent At',
        'column_updated_at' => 'Updated At',
    ],
];
