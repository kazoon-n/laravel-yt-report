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
        // All channels
        $channels = Channel::whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();
        
        foreach($channels as $channel){
            var_dump($channel['id']);
            var_dump($channel['ch_id']);
        }

        // Google Initialize
        $client = new Google_Client();
        $client->setApplicationName(env('APP_NAME'));
        $client->setDeveloperKey(getenv('API_KEY'));
        $youtube = new Google_Service_YouTube($client);

        foreach ($channels as $channel) {
            var_dump($channel['id']);
            var_dump($channel['ch_id']);
            // $ch_id = $channels['ch_id'];
            $ch_id = "UC-jHFUN5JxLjvihZkwT98Rg";
            $int_ch_id = $channel['ch_id'];

            /*
                Request for getting video list
            */
            // Set params for getting video list
            $params['channelId'] = $ch_id;
            $params['type'] = 'video';
            $params['maxResults'] = 50;

            $vdListResults = [];
            $pageToken = "default";

            while ($pageToken) {
                try {
                    $searchResponse = $youtube->search->listSearch('snippet', $params);
                    $pageToken = $searchResponse['nextPageToken'];
                    $params['pageToken'] = $pageToken;
                    array_map(function ($searchResult) use (&$vdListResults) {
                        $vdListResults[] = $searchResult;
                    }, $searchResponse['items']);
                } catch (Google_Service_Exception $e) {
                    echo htmlspecialchars($e->getMessage());
                    exit;
                } catch (Google_Exception $e) {
                    echo htmlspecialchars($e->getMessage());
                    exit;
                }
            }

            // Make all video list bellong to this channel
            $videosIds = [];
            foreach ($vdListResults as $result) {
                $videosIds[] = $result['id']['videoId'];
            }
            var_dump($videosIds);


            /*
                Request for getting video details
            */
            // Set params for getting video details
            $videoId = $videosIds;
            $query['id'] = $videoId;
            $query['maxResults'] = 50;
            $vdDetailResults = [];
            $pageToken = "default";
            

            while($pageToken){
                try {
                    $searchResponse = $youtube->videos->listVideos('snippet,statistics', $query);
                    $pageToken = $searchResponse['nextPageToken'];
                    $query['pageToken'] = $pageToken;
                    array_map(function ($searchResult) use (&$vdDetailResults) {
                        $vdDetailResults[] = $searchResult;
                    }, $searchResponse['items']);
                } catch (Google_Service_Exception $e) {
                    echo htmlspecialchars($e->getMessage());
                    exit;
                } catch (Google_Exception $e) {
                    echo htmlspecialchars($e->getMessage());
                    exit;
                }
            }

            // Make all video detial list bellong to this channel
            foreach ($vdDetailResults as $vdDetail) {
                $snippet = $vdDetail['snippet'];
                $statistics = $vdDetail['statistics'];
                var_dump($vdDetail['id']);
                var_dump($vdDetail['tags']);

                var_dump($snippet['title']);
                var_dump($snippet['thumbnails']['default']['url']);
                var_dump($snippet['description']);
                var_dump($statistics['viewCount']);
                var_dump($statistics['likeCount']);
                var_dump($statistics['commentCount']);
                // DB::transaction(function () use ($vdDetail, $int_ch_id) {
                //     $snippet = $vdDetail['snippet'];
                //     $statistics = $vdDetail['statistics'];

                //     Video::insert([
                //         'video_id' => $snippet['title'],
                //         'name' => $snippet['title'],
                //         'thumbnail' => $statistics['subscriberCount'],
                //         'description' => $snippet['thumbnails']['default']['url'],
                //         'views' => $snippet['thumbnails']['default']['url'],
                //         'likes' => $snippet['thumbnails']['default']['url'],
                //         'comments' => $snippet['thumbnails']['default']['url'],
                //         'channel_id' => $snippet['thumbnails']['default']['url'],
                //         'user_id' => \Auth::id()
                //     ]);

                // });
            }

        }


    }
}