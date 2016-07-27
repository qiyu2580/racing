<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RunRacing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Racing:Run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $SystemSwitch = \Cache::rememberForever('SystemSwitch', function() {
            return false;
        });

        if ($SystemSwitch) {
            $mRacing = \Cache::rememberForever('mRacing', function() {
                return \App\Racing::where('expired', 0)->OrderBy('awardTime', 'desc')->first();
            });

            $this->info('now: '. \Carbon\Carbon::now());
            $this->info('start: '. $mRacing->awardTime);
            if (\Carbon\Carbon::now()->subSeconds(40); >= $mRacing->awardTime) {
                \Cache::forever('periodNumber', $mRacing->periodNumber);
                \Event::fire(new \App\Events\CreateRacing($mRacing));
            }
        }
    }
}
