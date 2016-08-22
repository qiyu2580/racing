<?php

namespace App\Listeners;

use App\Racing as mRacing;
use App\Events\CreateRacing;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CreateRacingListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(mRacing $mRacing)
    {
        $this->mRacing = $mRacing;
    }

    /**
     * Handle the event.
     *
     * @param  CreateRacing  $event
     * @return void
     */
    public function handle(CreateRacing $event)
    {
        $TimeInterval = \Cache::rememberForever('TimeInterval', function() {
            return 5;
        });

        $awardNumbers = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])
            ->shuffle()->implode(',');
        $periodNumber = $event->currentRacing->periodNumber + 1;
        $awardTime = $this->now()->addMinute($TimeInterval);
        $mRacing = $this->mRacing->create(compact('awardNumbers', 'periodNumber', 'awardTime'));

        \Cache::forever('mRacing', $mRacing);

        $event->currentRacing->expired = true;
        $event->currentRacing->save();
    }

    private function now () {
        $hour = \Carbon\Carbon::now()->hour;
        $minute = \Carbon\Carbon::now()->minute;
        $second = \Carbon\Carbon::now()->second;
        return \Carbon\Carbon::now()->setTime($hour, $minute, 0);
    }
}
