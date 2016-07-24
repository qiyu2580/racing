<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $SystemSwitch = Cache::rememberForever('SystemSwitch', function() {
            return false;
        });

        if ($SystemSwitch) {
            \Cache::forever('periodNumber', $mRacing->periodNumber - 1);
            $mRacing = \App\Racing::where('expired', 0)->OrderBy('awardTime', 'desc')->first();
            \Cache::forever('periodNumber', $mRacing->periodNumber - 1);

            $schedule->call(function () use ($mRacing) {
                \Event::fire(new \App\Events\CreateRacing($mRacing));
            })->everyMinute()->when(function() use ($mRacing){
                return \Carbon\Carbon::now() >= $mRacing->awardTime;
            });
        }
    }
}
