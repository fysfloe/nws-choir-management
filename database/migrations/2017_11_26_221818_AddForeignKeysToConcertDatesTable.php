<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToConcertDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concert_dates', function(Blueprint $table)
        {
            $table->foreign('concert_id')->references('id')->on('concerts')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('concert_dates', function(Blueprint $table)
        {
            $table->dropForeign('concert_dates_concert_id_foreign');
        });
    }
}
