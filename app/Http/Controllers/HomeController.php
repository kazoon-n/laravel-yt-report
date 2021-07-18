<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;

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
}
