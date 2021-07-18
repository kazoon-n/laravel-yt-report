<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_Exception;
use Google_Exception;

class HogeBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:hoge';

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
        // require_once(dirname(__FILE__) . '/vendor/autoload.php');


        $client = new Google_Client();
        $client->setApplicationName(env('APP_NAME'));
        $client->setDeveloperKey(getenv('API_KEY'));

        $youtube = new Google_Service_YouTube($client);

        $keyword = "欅坂46";
        $params['q'] = $keyword;
        $params['type'] = 'video';
        $params['maxResults'] = 3;

        $videos = [];
        try {
            $searchResponse = $youtube->search->listSearch('snippet', $params);
            array_map(function ($searchResult) use (&$videos) {
                $videos[] = $searchResult;
            }, $searchResponse['items']);
        } catch (Google_Service_Exception $e) {
            echo htmlspecialchars($e->getMessage());
            exit;
        } catch (Google_Exception $e) {
            echo htmlspecialchars($e->getMessage());
            exit;
        }
        var_dump($searchResponse);
        exit;
    }
}
