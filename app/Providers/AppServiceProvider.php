<?php

namespace App\Providers;

use App\Http\Controllers\Controller;
use App\Models\Personnel\Message;
use App\Models\System\Setting;
use App\Models\System\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as ViewInstance;
use Spatie\Backup\Events\DumpingDatabase;

/**
 * @noinspection PhpUnused
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(DumpingDatabase::class, function (DumpingDatabase $event): void {
            $dbConnection = config('database.default');
            $dbConnectionString = is_string($dbConnection) ? $dbConnection : '';
            $dbDriver = config("database.connections.{$dbConnectionString}.driver");

            // SQLite uses sqlite3 binary, MySQL/MariaDB use mariadb-dump/mysqldump
            $binaryName = $dbDriver === 'sqlite' ? 'sqlite3' : 'mariadb-dump';

            $rawPath = config('database.dump_binary_path');
            $configuredPath = is_string($rawPath) ? $rawPath : '';
            $binaryPath = collect([
                $configuredPath,
                '/opt/local/bin',
                '/opt/homebrew/bin',
                '/usr/local/bin',
            ])->first(fn (string $path): bool => $path !== '' && is_file($path.'/'.$binaryName));

            if (is_string($binaryPath)) {
                $event->dbDumper->setDumpBinaryPath($binaryPath);
            }
        });

        Paginator::useBootstrapFive();

        // Blade cannot read the protected controller constant directly.
        View::share('perPageGridDetail', Controller::detailGridRows());

        $rawConnection = config('database.default');
        $databaseConnection = is_string($rawConnection) ? $rawConnection : '';
        $databasePath = config('database.connections.sqlite.database');
        $databaseIsUnavailable = $databaseConnection === 'sqlite'
            && $databasePath !== ':memory:'
            && is_string($databasePath)
            && ! is_file($databasePath);

        // Factory reset must be able to start even when the database cannot be read.
        $isResetCommand = $this->app->runningConsoleCommand('app:reset');

        if (! $isResetCommand && ! $databaseIsUnavailable && Schema::hasTable('settings')) {
            View::share('currencySymbol', Setting::getCurrencySymbol());
            $configuredTimezone = Setting::query()->with('timezone')->first()?->timezone?->zone;
            View::share('isMalaysiaTimezone', $configuredTimezone === 'Asia/Kuala_Lumpur');
        } else {
            View::share('currencySymbol', 'RM');
            View::share('isMalaysiaTimezone', true);
        }

        View::composer('layouts.topbar', function (ViewInstance $view): void {
            $unreadCount = 0;
            $unreadNotificationCount = 0;
            $latestNotifications = collect();

            if (Auth::check()) {
                /** @var User $user */
                $user = Auth::user();

                $unreadCount = Message::query()
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->count();

                $unreadNotificationCount = $user->unreadNotifications()->count();
                $latestNotifications = $user->notifications()->take(5)->get();
            }

            $view->with([
                'unreadCount' => $unreadCount,
                'unreadNotificationCount' => $unreadNotificationCount,
                'latestNotifications' => $latestNotifications,
            ]);
        });
    }
}
