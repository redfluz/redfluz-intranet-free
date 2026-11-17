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
    'title' => 'news Comments',
    'subtitle' => 'Moderate and manage user comments on published news articles.',
    'message' => [
        'posted' => 'news comment posted successfully.',
        'deleted_title' => 'Deleted!',
    ],
    'table' => [
        'column_id' => 'ID',
        'column_news_id' => 'news Article',
        'column_user_id' => 'Commenter',
        'column_parent_id' => 'Parent NewsComment',
        'column_comment' => 'NewsComment Content',
        'column_is_approved' => 'Approval Status',
        'column_created_at' => 'Commented At',
        'column_updated_at' => 'Updated At',
    ],
];
