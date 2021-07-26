<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InsertInitialUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'batch:initialUser';

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
        $user_name = env('INITIAL_USER_NAME');
        $user_email = env('INITIAL_USER_EMAIL');
        $user_pass = env('INITIAL_USER_PASS');

        User::create([
            'name' => $user_name,
            'email' => $user_email,
            'password' => Hash::make($user_pass),
        ]);
        
        return 0;
    }
}
