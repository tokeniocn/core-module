<?php

namespace Modules\Core\Module\Traits;

use Closure;
use Illuminate\Console\Scheduling\Schedule;

trait HasSchedule
{
    /**
     * @param Closure $callback
     * ```
     * $this->addSchedules(function($schedule) {
     *      $schedule->job(new TestJob)
     *      ->withoutOverlapping()
     *      ->everyMinute();
     * });
     *
     * ```
     */
    public function addSchedules(Closure $callback)
    {
        if ($this->app->runningInConsole()) {
            $this->app->resolving(Schedule::class, $callback);
        }
    }
}
