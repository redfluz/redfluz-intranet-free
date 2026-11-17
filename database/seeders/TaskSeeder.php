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

namespace Database\Seeders;

use App\Enums\Task\TaskPriority;
use App\Enums\Task\TaskStage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LogicException;
use Throwable;

class TaskSeeder extends Seeder
{
    /**
     * Seed sample task pipeline records for the task board and dashboard.
     *
     * @throws Throwable
     */
    public function run(): void
    {
        $now = now();
        $users = DB::table('users')
            ->orderBy('created_at')
            ->pluck('id')
            ->map(fn (mixed $id): string => $this->normalizeId($id))
            ->values();

        if ($users->isEmpty()) {
            throw new LogicException('TaskSeeder requires at least one user. Run SystemSeeder first.');
        }

        $creatorId = $this->userId('manager@example.com');

        foreach ($this->taskBlueprints() as $index => $blueprint) {
            $existingId = DB::table('tasks')
                ->where('reference_number', $blueprint['reference_number'])
                ->value('id');
            $existingCreatedAt = DB::table('tasks')
                ->where('reference_number', $blueprint['reference_number'])
                ->value('created_at');
            $assigneeId = isset($blueprint['assignee_email'])
                ? DB::table('users')->where('email', $blueprint['assignee_email'])->value('id')
                : $users[$index % $users->count()];

            if (! is_string($assigneeId) || $assigneeId === '') {
                $assignee = $blueprint['assignee_email'] ?? "at task index $index";

                throw new LogicException("TaskSeeder could not resolve assignee $assignee.");
            }

            DB::table('tasks')->updateOrInsert(
                ['reference_number' => $blueprint['reference_number']],
                [
                    'id' => is_string($existingId) ? $existingId : (string) Str::uuid7(),
                    'task_template_id' => null,
                    'source_type' => null,
                    'source_id' => null,
                    'assigned_to' => $assigneeId,
                    'created_by' => $creatorId,
                    'title' => $blueprint['title'],
                    'stage' => $blueprint['stage']->value,
                    'priority' => $blueprint['priority']->value,
                    'position' => $blueprint['position'],
                    'due_date' => $blueprint['due_offset'] === null
                        ? null
                        : $now->copy()->addDays($blueprint['due_offset'])->toDateString(),
                    'closed_at' => $blueprint['closed_offset'] === null
                        ? null
                        : $now->copy()->addDays($blueprint['closed_offset']),
                    'notes' => $blueprint['notes'],
                    'created_at' => is_string($existingCreatedAt)
                        ? $existingCreatedAt
                        : $now->copy()->subDays(20 - $index),
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]
            );
        }
    }

    private function userId(string $email): string
    {
        $id = DB::table('users')->where('email', $email)->value('id');

        if (! is_string($id) || $id === '') {
            throw new LogicException("TaskSeeder requires the seeded user $email.");
        }

        return $id;
    }

    /**
     * Validate and normalize a UUID loaded from the database.
     */
    private function normalizeId(mixed $id): string
    {
        if (! is_string($id) || $id === '') {
            throw new LogicException('TaskSeeder encountered an invalid database UUID.');
        }

        return $id;
    }

    /**
     * Define task records covering every dashboard and pipeline state.
     *
     * @return array<int, array{
     *     reference_number: string,
     *     title: string,
     *     stage: TaskStage,
     *     priority: TaskPriority,
     *     position: int,
     *     due_offset: int|null,
     *     closed_offset: int|null,
     *     notes: string,
     *     assignee_email?: string
     * }>
     */
    private function taskBlueprints(): array
    {
        return [
            [
                'reference_number' => 'TASK-SEED-001',
                'title' => 'Confirm initial client requirements',
                'stage' => TaskStage::INTRODUCTION,
                'priority' => TaskPriority::HIGH,
                'position' => 0,
                'due_offset' => -4,
                'closed_offset' => null,
                'notes' => 'Contact the client and confirm the scope, decision makers, and expected completion date.',
            ],
            [
                'reference_number' => 'TASK-SEED-002',
                'title' => 'Prepare onboarding document checklist',
                'stage' => TaskStage::INTRODUCTION,
                'priority' => TaskPriority::MEDIUM,
                'position' => 1,
                'due_offset' => 5,
                'closed_offset' => null,
                'notes' => 'Prepare the documents required before the first onboarding meeting.',
            ],
            [
                'reference_number' => 'TASK-SEED-003',
                'title' => 'Follow up on pending quotation',
                'stage' => TaskStage::FOLLOW_UP_1,
                'priority' => TaskPriority::HIGH,
                'position' => 0,
                'due_offset' => 0,
                'closed_offset' => null,
                'notes' => 'Confirm whether the quotation has been reviewed and record any requested amendments.',
            ],
            [
                'reference_number' => 'TASK-SEED-004',
                'title' => 'Send product comparison and pricing',
                'stage' => TaskStage::FOLLOW_UP_1,
                'priority' => TaskPriority::LOW,
                'position' => 1,
                'due_offset' => 7,
                'closed_offset' => null,
                'notes' => 'Send the requested comparison sheet together with the latest pricing.',
            ],
            [
                'reference_number' => 'TASK-SEED-005',
                'title' => 'Second follow-up with finance contact',
                'stage' => TaskStage::FOLLOW_UP_2,
                'priority' => TaskPriority::HIGH,
                'position' => 0,
                'due_offset' => -1,
                'closed_offset' => null,
                'notes' => 'Confirm the payment arrangement and expected approval date.',
            ],
            [
                'reference_number' => 'TASK-SEED-006',
                'title' => 'Arrange technical demonstration',
                'stage' => TaskStage::FOLLOW_UP_2,
                'priority' => TaskPriority::MEDIUM,
                'position' => 1,
                'due_offset' => 3,
                'closed_offset' => null,
                'notes' => 'Coordinate the demonstration schedule with the client and technical team.',
            ],
            [
                'reference_number' => 'TASK-SEED-007',
                'title' => 'Finalize implementation schedule',
                'stage' => TaskStage::PROCEED,
                'priority' => TaskPriority::HIGH,
                'position' => 0,
                'due_offset' => 2,
                'closed_offset' => null,
                'notes' => 'Agree on milestones, owners, and delivery dates before implementation starts.',
            ],
            [
                'reference_number' => 'TASK-SEED-008',
                'title' => 'Collect signed acceptance form',
                'stage' => TaskStage::PROCEED,
                'priority' => TaskPriority::MEDIUM,
                'position' => 1,
                'due_offset' => null,
                'closed_offset' => null,
                'notes' => 'Obtain and file the signed acceptance form from the authorized representative.',
            ],
            [
                'reference_number' => 'TASK-SEED-009',
                'title' => 'On-site network cabinet inspection completed',
                'stage' => TaskStage::SUCCESS,
                'priority' => TaskPriority::HIGH,
                'position' => 0,
                'due_offset' => -8,
                'closed_offset' => -7,
                'notes' => 'Switch health, patch-panel labels, cable condition, and cabinet ventilation were checked with no outstanding faults.',
                'assignee_email' => 'technician.field@example.com',
            ],
            [
                'reference_number' => 'TASK-SEED-010',
                'title' => 'Customer laptop setup completed',
                'stage' => TaskStage::SUCCESS,
                'priority' => TaskPriority::MEDIUM,
                'position' => 1,
                'due_offset' => -3,
                'closed_offset' => -2,
                'notes' => 'Operating-system updates, endpoint protection, user profile, printer access, and handover checks were completed.',
                'assignee_email' => 'technician.field@example.com',
            ],
            [
                'reference_number' => 'TASK-SEED-011',
                'title' => 'Printer repair unsuccessful - parts unavailable',
                'stage' => TaskStage::FAILURE,
                'priority' => TaskPriority::MEDIUM,
                'position' => 0,
                'due_offset' => -6,
                'closed_offset' => -5,
                'notes' => 'The failed controller board is discontinued. Findings were recorded and the customer was advised to replace the unit.',
                'assignee_email' => 'technician.field@example.com',
            ],
            [
                'reference_number' => 'TASK-SEED-012',
                'title' => 'Legacy server recovery unsuccessful',
                'stage' => TaskStage::FAILURE,
                'priority' => TaskPriority::LOW,
                'position' => 1,
                'due_offset' => -32,
                'closed_offset' => -31,
                'notes' => 'Recovery was stopped after storage diagnostics confirmed multiple failed disks and no usable backup image.',
                'assignee_email' => 'technician.field@example.com',
            ],
            [
                'reference_number' => 'TASK-SEED-013',
                'title' => 'Quarterly workstation maintenance completed',
                'stage' => TaskStage::SUCCESS,
                'priority' => TaskPriority::MEDIUM,
                'position' => 2,
                'due_offset' => -46,
                'closed_offset' => -45,
                'notes' => 'Preventive maintenance, firmware checks, and the customer handover were completed.',
                'assignee_email' => 'technician.field@example.com',
            ],
        ];
    }
}
