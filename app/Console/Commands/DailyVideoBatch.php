<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_Exception;
use Google_Exception;
use App\Models\DailyVideo;
use App\Models\Video;
use Illuminate\Support\Facades\DB;

class DailyVideoBatch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:getDailyVideo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command of getting daily videos';

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
        // Google Initialize
        $client = new Google_Client();
        $client->setApplicationName(env('APP_NAME'));
        $client->setDeveloperKey(getenv('API_KEY'));
        $youtube = new Google_Service_YouTube($client);

        // set channels
        $int_ch_id = 8;
        $date = '2021-07-23';

        $videos = Video::where('channel_id', '=', $int_ch_id)
        ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        foreach($videos as $video){
            var_dump($video);
            $query['id'] = $video['video_id'];
            $vdDetails = [];
            $pageToken = "default";

            while ($pageToken) {
                try {
                    $searchResponse = $youtube->videos->listVideos('statistics', $query);
                    $pageToken = $searchResponse['nextPageToken'];
                    $query['pageToken'] = $pageToken;
                    array_map(function ($searchResult) use (&$vdDetails) {
                        $vdDetails[] = $searchResult;
                    }, $searchResponse['items']);
                } catch (Google_Service_Exception $e) {
                    echo htmlspecialchars($e->getMessage());
                    exit;
                } catch (Google_Exception $e) {
                    echo htmlspecialchars($e->getMessage());
                    exit;
                }
            }

            DB::transaction(function () use ($date, $vdDetails, $video) {
                $statistics = $vdDetails[0]['statistics'];
                DailyVideo::insert([
                    'date' => $date,
                    'views' => $statistics['viewCount'] - $video['views'],
                    'likes' => $statistics['likeCount'] - $video['likes'],
                    'comments' => $statistics['commentCount'] - $video['comments'],
                    'video_id' => $video['id']
                ]);
            });
        }

        return 0;
    }
}
