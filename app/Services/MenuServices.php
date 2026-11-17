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

namespace App\Services;

use App\Models\Menu\Folder;
use App\Models\Menu\FolderPermission;
use App\Models\Menu\Page;
use App\Models\Menu\PageGrouping;
use App\Models\Menu\PageGroupingPermission;
use App\Models\Menu\PagePermission;
use App\Models\System\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class MenuServices
{
    /**
     * Fetch nested menu structure for a user with pre-calculated URLs and active states.
     *
     * @param  User|null  $user  The authenticated user.
     * @return array<int, array<string, mixed>>
     */
    public function getMenusForUser(?User $user): array
    {
        if (! $user || ! $user->role_id) {
            return [];
        }

        $roleId = $user->role_id;

        /** @var Collection<int, Folder> $folders */
        $folders = FolderPermission::query()
            ->with('folder')
            ->where('role_id', $roleId)
            ->where('can_read', true)
            ->get()
            ->pluck('folder')
            ->filter()
            ->sortBy(function (mixed $folder): int {
                if (! $folder instanceof Folder) {
                    return PHP_INT_MAX;
                }

                return $folder->order;
            })
            ->values();

        if ($folders->isEmpty()) {
            return [];
        }

        $folderIds = $folders->pluck('id');

        /** @var Collection<int, PageGrouping> $pageGroupings */
        $pageGroupings = PageGroupingPermission::query()
            ->with('pageGrouping')
            ->where('role_id', $roleId)
            ->where('can_read', true)
            ->get()
            ->pluck('pageGrouping')
            ->filter()
            ->whereIn('folder_id', $folderIds);

        /** @var Collection<int|string, Collection<int, PageGrouping>> $groupingsByFolder */
        $groupingsByFolder = $pageGroupings->groupBy('folder_id');

        /** @var Collection<int, Page> $pages */
        $pages = PagePermission::query()
            ->with('page')
            ->where('role_id', $roleId)
            ->where('can_read', true)
            ->get()
            ->pluck('page')
            ->filter()
            ->whereIn('folder_id', $folderIds);

        /** @var array<int, array<string, mixed>> $result */
        $result = $folders->map(
            function (Folder $folder) use ($pages, $groupingsByFolder): array {
                /** @var Collection<int, Page> $folderPages */
                $folderPages = $pages
                    ->where('folder_id', $folder->id)
                    ->sortBy(fn (Page $page): int => $page->order)
                    ->values();

                /** @var int|string $folderId */
                $folderId = $folder->id;

                /** @var Collection<int, PageGrouping> $folderGroupings */
                $folderGroupings = $groupingsByFolder->get($folderId, collect())
                    ->sortBy(function (mixed $grouping): int {
                        if (! $grouping instanceof PageGrouping) {
                            return PHP_INT_MAX;
                        }

                        return $grouping->order;
                    })
                    ->values();

                $groupings = [];

                foreach ($folderGroupings as $grouping) {
                    $groupingPages = $folderPages
                        ->filter(fn (Page $page): bool => $page->page_grouping_id === $grouping->id)
                        ->map(fn (Page $page): array => $this->mapPage($page))
                        ->values()
                        ->toArray();

                    if (! empty($groupingPages)) {
                        $groupings[] = [
                            'id' => $grouping->id,
                            'name' => $grouping->name,
                            'icon' => $grouping->icon,
                            'pages' => $groupingPages,
                        ];
                    }
                }

                $ungroupedPages = $folderPages
                    ->filter(fn (Page $page): bool => empty($page->page_grouping_id))
                    ->map(fn (Page $page): array => $this->mapPage($page))
                    ->values()
                    ->toArray();

                if (! empty($ungroupedPages)) {
                    $groupings[] = [
                        'id' => null,
                        'name' => null,
                        'icon' => null,
                        'pages' => $ungroupedPages,
                    ];
                }

                return [
                    'id' => $folder->id,
                    'name' => $folder->name,
                    'icon' => $folder->icon,
                    'groupings' => $groupings,
                ];
            }
        )->values()->toArray();

        return $result;

    }

    /**
     * Map a page record to a menu page array with URL and active state.
     *
     * @param  Page  $page  The page model instance.
     * @return array<string, mixed> The mapped page array.
     */
    private function mapPage(Page $page): array
    {
        /** @var string $routeName */
        $routeName = $page->route_name ?? '';
        $hasRoute = $routeName !== ''
            && Route::has($routeName);

        return [
            'id' => $page->id,
            'name' => $page->name,
            'icon' => $page->icon,
            'url' => $hasRoute
                ? route($routeName)
                : '#',
            'is_active' => $routeName !== ''
                && request()->routeIs($routeName.'*'),
        ];
    }
}
