<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Models\Channel;

class AllRetrivalVideoBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:getAllRetrivalVideo';

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
        Log::debug("====Start getAllVideo====");
        $channels = Channel::whereNull('deleted_at')
        ->orderBy('id', 'DESC')
        ->get();
        foreach ($channels as $channel) {
            $this->call('retriveVideo', ["id" => $channel['id'], "ch_id" => $channel['ch_id']]);
        }
        Log::debug("====End getAllVideo====");
        return 0;

    }
}
