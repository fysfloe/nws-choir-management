<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Role;
use App\User;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminuser:create';

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
        // Create the user.
        $user = User::create([
            'firstname' => 'Florian',
            'surname' => 'Csizmazia',
            'email' => 'florian.csizmazia@gmail.com',
            'gender' => 'm',
            'birthdate' => '1991-12-23',
            'password' => bcrypt('asdf12')
        ]);

        // Attach the admin role.
        $admin = Role::where('name', '=', 'admin')->first();

        $user->attachRole($admin);

        $this->info('Created admin user!');
    }
}
