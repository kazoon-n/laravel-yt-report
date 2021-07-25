<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\DailyVideo;
use App\Models\Video;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_YouTube;
use Google_Service_Exception;
use Google_Exception;
use Illuminate\Support\Facades\DB;
use DateTimeImmutable;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $channels = Channel::where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        return view('channel_table', compact('channels'));
    }

    public function channel()
    {
        $flag = false;
        return view('add_channel', compact('flag'));
    }

    public function search_channel(Request $request)
    {
        $posts = $request->all();
        
        $client = new Google_Client([
            'verify' => false
        ]);
        $client->setDeveloperKey(getenv('API_KEY'));
        $youtube = new Google_Service_YouTube($client);

        $channel_url = $posts['channel_url'];

        if(preg_match('/UC.*/',$channel_url, $channel_id) == 0){
            $request->validate(['content' => 'required']);
        }        

        $keyword = $channel_id;
        $params['channelId'] = $keyword;
        $params['type'] = 'channel';
        $params['maxResults'] = 1;

        $channels = [];
        try {
            $searchResponse = $youtube->search->listSearch('snippet', $params);

            array_map(function ($searchResult) use (&$channels) {
                $channels[] = $searchResult;
            }, $searchResponse['items']);

        } catch (Google_Service_Exception $e) {
            echo htmlspecialchars($e->getMessage());
        } catch (Google_Exception $e) {
            echo htmlspecialchars($e->getMessage());
         }

        $channel = $channels[0];        
        $flag = true;

        return view('add_channel', compact('channel', 'flag'));
    }

    public function add_channel(Request $request)
    {
        $posts = $request->all();

        $client = new Google_Client([
            'verify' => false
        ]);
        $client->setDeveloperKey(getenv('API_KEY'));
        $youtube = new Google_Service_YouTube($client);

        $channel_id = $posts['channel_id'];

        $keyword = $channel_id;
        $params['id'] = $keyword;
        $params['maxResults'] = 1;

        $channel_detail = [];
        try {
            $searchResponse = $youtube->channels->listChannels('snippet,statistics', $params);

            array_map(function ($searchResult) use (&$channel_detail) {
                $channel_detail[] = $searchResult;
            }, $searchResponse['items']);
        } catch (Google_Service_Exception $e) {
            echo htmlspecialchars($e->getMessage());
        } catch (Google_Exception $e) {
            echo htmlspecialchars($e->getMessage());
        }

        DB::transaction(function () use ($channel_detail) {
            $snippet = $channel_detail[0]['snippet'];
            $statistics = $channel_detail[0]['statistics'];

            Channel::insert([
                'ch_id' => $channel_detail[0]['id'],
                'name' => $snippet['title'],
                'subscriber' => $statistics['subscriberCount'],
                'icon' => $snippet['thumbnails']['default']['url'],
                'user_id' => \Auth::id()
            ]);

        });

        return redirect(route('home'));
    }

    public function video_list($id)
    {
        $videos = Video::where('channel_id', '=', $id)
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        return view('video_list', compact('videos'));
    }

    public function video_detail($id, Request $request)
    {
        $video = Video::where('id', '=', $id)
            ->whereNull('deleted_at')
            ->orderBy('id', 'DESC')
            ->get();

        $params = $request->all();
        $filterd_date = [];
        if(!$params or !$params['date_controll']){
            $metrics = "views";
            $first_day_of_month = (new DateTimeImmutable)->modify('first day of')->format('Y-m-d');
            $last_day_of_month = (new DateTimeImmutable)->modify('last day of')->format('Y-m-d');
            $filterd_date[] = $first_day_of_month;
            $filterd_date[] = "to";
            $filterd_date[] = $last_day_of_month;
        }else{
            $filterd_date = explode(" ", $params["date_controll"]);
        }

        if(array_key_exists('metrics', $params)){
            $metrics = $params['metrics'] == "" ? "views" : $params['metrics'];
        }

        $daily_videos = DailyVideo::select('date', $metrics)
            ->where('video_id', '=', $id)
            ->whereBetween('date', [$filterd_date[0], $filterd_date[2]])
            ->whereNull('deleted_at')
            ->orderBy('date', 'ASC')
            ->get();

        $dataPoints = [];
        $dataPoints[] = array('Date', ucfirst($metrics));
        foreach($daily_videos as $daily_video){
            $dataPoints[] = array($daily_video['date'], $daily_video[$metrics]);
        }

        return view('video_detail', compact('id', 'video', 'dataPoints'));
    }
}
