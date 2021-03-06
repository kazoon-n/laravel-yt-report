<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_Exception;
use Google_Exception;
use App\Models\Channel;
use App\Models\Video;
use Symfony\Component\VarDumper\VarDumper;

class HogeBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:hoge {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command Batch test';

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
        $hoge = $this->argument('date');
        if(!$hoge){
            echo "cara";
        }
        var_dump($hoge);
        return 0;

    }
}