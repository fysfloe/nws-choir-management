<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Role;
use App\User;

class AddUsernames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:add_usernames';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds usernames.';

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
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('username', '=', NULL)->get();

        foreach ($users as $user) {
            $user->username = $user->firstname . $user->surname;
            $user->save();

            $this->info('Added username for: ' . $user->username);
        }
    }
}
