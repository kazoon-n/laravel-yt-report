<?php

namespace App\Console\Commands;

use App\Models\Channel;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AllRetrivalDailyVideoBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:getDailyVideoAll {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        Log::debug("====Start getDailyVideo====");

        // params
        $date = $this->argument('date');
        if(!$date){
            $date = date("Y-m-d");
        }

        $channels = Channel::whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();
        foreach($channels as $channel){
            $this->call('getDailyVideo', ["int_ch_id" => $channel['id'], "date" => $date]);
        }
        Log::debug("====End getDailyVideo====");
        return 0;
    }
}
