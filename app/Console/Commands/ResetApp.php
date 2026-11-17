<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;

class ResetApp extends Command
{
    protected $signature = 'app:reset
                            {--force : Skip confirmation prompt}';

    protected $description = 'Full factory reset — wipe database, storage, caches completely';

    /**
     * Wipe the entire application clean.
     *
     * Destroys everything: recreates an empty SQLite file after removing its
     * journal files, or drops all MySQL tables. Purges all uploaded files,
     * logs, compiled views, sessions, and caches. Does not re-migrate or
     * re-seed — run app:install afterwards for a fresh setup.
     *
     * @return int The command exit code.
     */
    public function handle(): int
    {
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║          Redfluz Accounting — Full Factory Reset          ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');
        $this->warn('  For SQLite, stop app servers, queue workers and schedulers before running this reset.');

        if (! $this->option('force')) {
            $this->warn('  WARNING: This will destroy EVERYTHING:');
            $this->line('    • All database tables and data');
            $this->line('    • All uploaded files and attachments');
            $this->line('    • All logs, sessions, and cached data');
            $this->line('');
            $this->line('  Run app:install afterwards to set up fresh.');
            $this->line('');

            if (! $this->confirm('  Are you sure you want to continue?')) {
                $this->info('  Reset cancelled.');

                return self::SUCCESS;
            }
        }

        // Step 1: Clear caches
        $this->info('▸ Clearing caches...');
        try {
            $this->call('cache:clear', ['--quiet' => true]);
        } catch (Exception) {
            // Database may not exist yet (e.g. SQLite file deleted). Nothing to clear.
        }
        $this->call('config:clear', ['--quiet' => true]);
        $this->call('route:clear', ['--quiet' => true]);
        $this->call('view:clear', ['--quiet' => true]);

        // Compiled package/service manifests are not covered by the
        // cache:clear family, yet they snapshot the previous install.
        foreach (File::files(base_path('bootstrap/cache')) as $file) {
            if ($file->getFilename() === '.gitignore') {
                continue;
            }

            File::delete($file->getPathname());
        }
        $this->info('  ✓ Caches cleared');

        // Step 2: Purge storage
        $this->info('');
        $this->info('▸ Purging storage...');
        $this->purgeDirectory(storage_path('app'));
        $this->purgeDirectory(storage_path('framework/cache/data'));
        $this->purgeDirectory(storage_path('framework/sessions'));
        $this->purgeDirectory(storage_path('framework/views'));
        $this->purgeDirectory(storage_path('framework/testing'));
        $this->purgeDirectory(storage_path('logs'));
        $this->info('  ✓ Storage purged');

        // Step 3: Destroy database
        $this->info('');
        $this->info('▸ Destroying database...');

        /** @var string $dbConnection */
        $dbConnection = config('database.default');
        $database = DB::connection($dbConnection);

        if ($database->getDriverName() === 'sqlite') {
            $dbPath = $database->getConfig('database');

            if (! is_string($dbPath) || $dbPath === '') {
                $this->error('  SQLite database path is not configured.');

                return self::FAILURE;
            }

            try {
                $this->resetSqliteDatabase($dbPath);
            } catch (Exception $e) {
                $this->error('  SQLite reset failed: '.$e->getMessage());

                return self::FAILURE;
            }
        } else {
            try {
                DB::connection()->getPdo();
                $this->call('migrate:reset', [
                    '--force' => true,
                    '--quiet' => true,
                ]);
                $this->info('  ✓ All MySQL tables dropped');
            } catch (Exception $e) {
                $this->error('  ✗ Database connection failed!');
                $this->line('  '.$e->getMessage());

                return self::FAILURE;
            }
        }

        // Step 4: Remove storage link
        if (file_exists(public_path('storage'))) {
            File::delete(public_path('storage'));
            $this->info('');
            $this->info('  ✓ Storage link removed');
        }

        // Step 5: Done
        $this->info('');
        $this->info('╔════════════════════════════════════════════════════════════╗');
        $this->info('║                  Factory Reset Complete!                  ║');
        $this->info('╠════════════════════════════════════════════════════════════╣');
        $this->info('║                                                           ║');
        $this->info('║  Everything has been wiped clean.                         ║');
        $this->info('║                                                           ║');
        $this->info('║  To set up fresh:                                         ║');
        $this->info('║    php artisan app:install                                ║');
        $this->info('║                                                           ║');
        $this->info('╚════════════════════════════════════════════════════════════╝');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Replace the configured SQLite database and remove its stale journal files.
     *
     * @param  string  $dbPath  The configured SQLite file path or in-memory database name.
     * @return void The database is left empty, ready for app:install.
     *
     * @throws RuntimeException When the path is unsupported or a file cannot be removed or created.
     */
    private function resetSqliteDatabase(string $dbPath): void
    {
        // Release this process's PDO handles before unlinking the database or journals.
        foreach (DB::getConnections() as $name => $connection) {
            if ($connection->getDriverName() === 'sqlite') {
                DB::purge($name);
            }
        }

        if ($dbPath === ':memory:' || str_contains($dbPath, '?mode=memory') || str_contains($dbPath, '&mode=memory')) {
            $this->info('  In-memory SQLite connections cleared; no files to remove.');

            return;
        }

        if (str_starts_with($dbPath, 'file:')) {
            throw new RuntimeException('Use a filesystem database path instead of a SQLite URI for app:reset.');
        }

        $dbPath = realpath($dbPath) ?: realpath(base_path($dbPath)) ?: $dbPath;
        $normalizedPath = str_replace('\\', '/', $dbPath);
        if (! str_starts_with($normalizedPath, '/') && preg_match('/^[A-Za-z]:\//', $normalizedPath) !== 1) {
            $dbPath = base_path($dbPath);
        }

        // Target only this database, not unrelated files matched by database.*.
        foreach ([$dbPath, $dbPath.'-wal', $dbPath.'-shm', $dbPath.'-journal'] as $file) {
            if ((File::exists($file) || is_link($file)) && ! File::delete($file)) {
                throw new RuntimeException('Could not delete '.$file);
            }
        }

        File::ensureDirectoryExists(dirname($dbPath));
        if (File::put($dbPath, '') === false) {
            throw new RuntimeException('Could not create '.$dbPath);
        }
        clearstatcache(true, $dbPath);

        $this->info('  SQLite database recreated; WAL, SHM and journal files removed.');
    }

    /**
     * Delete all files and subdirectories inside a directory.
     *
     * Preserves the directory itself and any .gitkeep/.gitignore files so the
     * folder structure remains intact for Laravel and version control.
     *
     * @param  string  $path  Absolute path to the directory to purge.
     */
    private function purgeDirectory(string $path): void
    {
        if (! is_dir($path)) {
            return;
        }

        /** @var string $directory */
        foreach (File::directories($path) as $directory) {
            File::deleteDirectory($directory);
        }

        foreach (File::files($path) as $file) {
            if (in_array($file->getFilename(), ['.gitkeep', '.gitignore'], true)) {
                continue;
            }

            File::delete($file->getPathname());
        }
    }
}
