<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Role;
use App\Permission;

class CreateRolesAndPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rolesandpermissions:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates admin and member roles and permissions.';

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
        // Create the admin role.
        $admin = Role::where('name', '=', 'admin')->first();
        if (!$admin) {
            $admin = new Role();
            $admin->name = 'admin';
            $admin->display_name = 'Administrator';
            $admin->description = 'User is allowed to manage and edit other users, create concerts and anything else.'; // optional
            $admin->save();
        }

        // Create the member role.
        $member = Role::where('name', '=', 'member')->first();
        if (!$member) {
            $member = new Role();
            $member->name = 'member';
            $member->display_name = 'Member';
            $member->description = 'User is allowed to take part in concerts';
            $member->save();
        }

        // Create the manageConcerts permission.
        if (!Permission::where('name', '=', 'manageConcerts')->first()) {
            $manageConcerts = new Permission();
            $manageConcerts->name = 'manageConcerts';
            $manageConcerts->display_name = 'Manage Concerts';
            $manageConcerts->description = 'Create, edit and delete concerts.';
            $manageConcerts->save();

            $admin->attachPermission($manageConcerts);
        }

        // Create the manageUsers permission.
        if (!Permission::where('name', '=', 'manageUsers')->first()) {
            $manageUsers = new Permission();
            $manageUsers->name = 'manageUsers';
            $manageUsers->display_name = 'Manage Users';
            $manageUsers->description = 'Create, edit and delete users.';
            $manageUsers->save();

            $admin->attachPermission($manageUsers);
        }

        // Create the manageRehearsals permission.
        if (!Permission::where('name', '=', 'manageRehearsals')->first()) {
            $manageRehearsals = new Permission();
            $manageRehearsals->name = 'manageRehearsals';
            $manageRehearsals->display_name = 'Manage Rehearsals';
            $manageRehearsals->description = 'Create, edit and delete rehearsals.';
            $manageRehearsals->save();

            $admin->attachPermission($manageRehearsals);
        }

        // Create the manageVoices permission.
        if (!Permission::where('name', '=', 'manageVoices')->first()) {
            $manageVoices = new Permission();
            $manageVoices->name = 'manageVoices';
            $manageVoices->display_name = 'Manage Voices';
            $manageVoices->description = 'Create, edit and delete voices.';
            $manageVoices->save();

            $admin->attachPermission($manageVoices);
        }

        // Create the manageSemesters permission.
        if (!Permission::where('name', '=', 'manageSemesters')->first()) {
            $manageSemesters = new Permission();
            $manageSemesters->name = 'manageSemesters';
            $manageSemesters->display_name = 'Manage Semesters';
            $manageSemesters->description = 'Create, edit and delete semesters.';
            $manageSemesters->save();

            $admin->attachPermission($manageSemesters);
        }

        // Create the manageProjects permission.
        if (!Permission::where('name', '=', 'manageProjects')->first()) {
            $manageProjects = new Permission();
            $manageProjects->name = 'manageProjects';
            $manageProjects->display_name = 'Manage Projects';
            $manageProjects->description = 'Create, edit and delete projects.';
            $manageProjects->save();

            $admin->attachPermission($manageProjects);
        }

        $this->info('Created roles and permissions!');
    }
}
