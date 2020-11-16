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
