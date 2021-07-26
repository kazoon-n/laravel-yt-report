<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_Exception;
use Google_Exception;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class RetriveInitialVideoBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'retriveVideo {id} {ch_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Retrive Initial Video';

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
        //Params Initialize
        $int_ch_id = $this->argument('id');
        $ch_id = $this->argument('ch_id');

        // Google Initialize
        $client = new Google_Client();
        $client->setApplicationName(env('APP_NAME'));
        $client->setDeveloperKey(getenv('API_KEY'));
        $youtube = new Google_Service_YouTube($client);

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
        $videoIds = [];
        foreach ($vdListResults as $result) {
            $videoIds[] = $result['id']['videoId'];
        }


        /*
            Request for getting video details
        */
        // Set params for getting video details
        $query_videoIds = array_chunk($videoIds,50);
 
        foreach($query_videoIds as $query_videoId){
            $query['id'] = $query_videoId;
            $query['maxResults'] = 50;
            $vdDetailResults = [];
            $pageToken = "default";

            while ($pageToken) {
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
                DB::transaction(function () use ($vdDetail, $int_ch_id) {
                    $snippet = $vdDetail['snippet'];
                    $statistics = $vdDetail['statistics'];
                    $upsert_video = [
                        'video_id' => $vdDetail['id'],
                        'name' => $snippet['title'],
                        'thumbnail' => $snippet['thumbnails']['default']['url'],
                        'description' => mb_substr($snippet['description'], 0, 190),
                        'views' => $statistics['viewCount'],
                        'likes' => $statistics['likeCount'],
                        'comments' => $statistics['commentCount'],
                        'channel_id' => $int_ch_id
                    ];
                    var_dump($upsert_video);
                    $lookup = Video::where('video_id', $vdDetail['id']);
                    $lookup->exists() ? $lookup->update($upsert_video) : $lookup->insert($upsert_video);
                    
                });
            }

        }
        return 0;

    }
}
