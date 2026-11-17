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

namespace App\Console\Commands;

use App\Http\Controllers\Menu\FolderPermissionController;
use App\Http\Controllers\Menu\PageGroupingPermissionController;
use App\Http\Controllers\Menu\PagePermissionController;
use App\Models\System\User;
use Illuminate\Console\Command;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ViewErrorBag;
use Throwable;

class VerifyInlineJs extends Command
{
    protected $signature = 'verify:inline-js';

    protected $description = 'Syntax check inline JavaScript in rendered module pages';

    /**
     * One GET page per module that carries inline scripts.
     *
     * @var array<string, array{0: class-string, 1: string}>
     */
    private const array PAGES = [
        'folder-permissions' => [FolderPermissionController::class, 'index'],
        'page-permissions' => [PagePermissionController::class, 'index'],
        'page-grouping-perms' => [PageGroupingPermissionController::class, 'index'],
    ];

    public function handle(): int
    {
        $user = User::query()->whereHas('role', fn ($query) => $query->where('name', 'Super Admin'))->first();

        if ($user === null) {
            $this->error('No Super Admin user to render with.');

            return self::FAILURE;
        }

        Auth::login($user);
        app('view')->share('errors', new ViewErrorBag);

        $directory = sys_get_temp_dir().'/inline-js-'.getmypid();

        if (! is_dir($directory) && ! mkdir($directory, 0777, true) && ! is_dir($directory)) {
            $this->error('Could not create a temporary directory.');

            return self::FAILURE;
        }

        $totalScripts = 0;
        $totalErrors = 0;

        foreach (self::PAGES as $label => [$class, $method]) {
            $request = Request::create('/');
            $request->setLaravelSession(app('session.store'));
            app()->instance('request', $request);

            try {
                // Resolved through the container rather than constructed directly,
                // because a controller declares what it depends on in its
                // constructor and a plain new leaves those arguments unfilled.
                $response = app($class)->{$method}($request);
                $html = $response instanceof ViewContract ? $response->render() : '';
            } catch (Throwable $e) {
                $this->warn(sprintf('%-20s skipped: %s', $label, substr($e->getMessage(), 0, 50)));

                continue;
            }

            preg_match_all('/<script(?![^>]*\bsrc=)[^>]*>(.*?)<\/script>/s', $html, $matches);

            $errors = [];
            foreach ($matches[1] as $index => $script) {
                if (trim($script) === '') {
                    continue;
                }

                $totalScripts++;
                $file = $directory.'/script-'.$index.'.js';
                file_put_contents($file, $script);

                exec('node --check '.escapeshellarg($file).' 2>&1', $output, $status);

                if ($status !== 0) {
                    $errors[] = $index.': '.implode(' | ', array_slice($output, 0, 2));
                }

                unlink($file);
                $output = [];
            }

            $totalErrors += count($errors);
            $this->line(sprintf('%-20s scripts=%-3d errors=%d', $label, count($matches[1]), count($errors)));

            foreach ($errors as $error) {
                $this->error('    script #'.$error);
            }
        }

        @rmdir($directory);

        $this->newLine();
        $this->info(sprintf('Inline scripts checked: %d, syntax errors: %d', $totalScripts, $totalErrors));

        return $totalErrors === 0 ? self::SUCCESS : self::FAILURE;
    }
}
