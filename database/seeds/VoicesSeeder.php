<?php

use Illuminate\Database\Seeder;

class VoicesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        //Empty the voices table
        DB::table('voices')->delete();

        DB::table('voices')->insert([
            'name' => 'Bass',
            'slug' => 'bass'
        ]);

        DB::table('voices')->insert([
            'name' => 'Tenor',
            'slug' => 'tenor'
        ]);

        DB::table('voices')->insert([
            'name' => 'Alt',
            'slug' => 'alt'
        ]);

        DB::table('voices')->insert([
            'name' => 'Sopran',
            'slug' => 'sopran'
        ]);
    }
}
