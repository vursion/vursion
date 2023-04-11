<?php

namespace Vursion\Vursion;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Vursion\Vursion\PublishCommand;
use Vursion\Vursion\VursionCommand;

class VursionServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('vursion:heartbeat')->everyTenMinutes();
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('vursion.php'),
            ], 'config');
        }

        if ((version_compare(app()->version(), '9.21') >= 0) && class_exists(\Illuminate\Foundation\Console\AboutCommand::class)) {
            \Illuminate\Foundation\Console\AboutCommand::add('Vursion', [
                'Enabled' => fn() => (config('vursion.enabled') ? '<fg=green>TRUE</>' : '<fg=yellow>FALSE</>'),
                'Key'     => fn() => config('vursion.key'),
                'Route'   => fn() => ((version_compare(app()->version(), '5.6.12') >= 0) ? \Illuminate\Support\Facades\URL::signedRoute('vursion') : route('vursion')),
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'vursion');

        $this->commands([
            VursionCommand::class,
            PublishCommand::class,
        ]);
    }
}
