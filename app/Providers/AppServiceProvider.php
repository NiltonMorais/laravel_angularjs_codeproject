<?php

namespace CodeProject\Providers;

use CodeProject\Entities\ProjectTask;
use CodeProject\Events\TaskChanged;
use CodeProject\Events\TaskWasIncluded;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (app()->runningInConsole()) {
            return;
        }

        ProjectTask::created(function ($task) {
            Event::fire(new TaskWasIncluded($task));
        });

        ProjectTask::updated(function ($task) {
            Event::fire(new TaskChanged($task));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
