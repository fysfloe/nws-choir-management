<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        $this->call('CountriesSeeder');
        $this->command->info('Seeded the countries!');

        $this->call('UsersSeeder');
        $this->command->info('Seeded the users!');
    }
}
