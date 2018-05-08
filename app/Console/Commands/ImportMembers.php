<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Role;
use App\User;

use File;

class ImportMembers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'members:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates my admin user.';

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
        $users = json_decode(File::get('members.json'), true);
        $member = Role::where('name', '=', 'member')->first();
        $passwords = [];

        foreach ($users as $key => $userArray) {
            $password = $this->randomPassword();
            $userArray['password'] = bcrypt($password);
            // Create the user.
            if (User::where('email', '=', $userArray['email'])->first()) {
                continue;
            }
            $user = User::create($userArray);
            $user->attachRole($member);

            $passwords[] = [
                'email' => $userArray['email'],
                'password' => $password
            ];

            $this->info('Created user for: ' . $userArray['email']);
        }

        File::put('passwords.json', json_encode($passwords));
    }

    private function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
}
