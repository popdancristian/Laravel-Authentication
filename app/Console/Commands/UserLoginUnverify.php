<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\DB;
use App\Models\UserLogin;
use Illuminate\Console\Command;

class UserLoginUnverify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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

        DB::table('user_logins')
        ->where('id', $this->argument('user'))
        ->update([
            'is_verified' => 0,
            'updated_at' => now()
        ]);
        // $user = UserLogin::find($this->argument('userId'));

        // $user->is_verified = 0;

        // $user->update();
    }
}
