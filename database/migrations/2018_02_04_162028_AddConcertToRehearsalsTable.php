<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConcertToRehearsalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rehearsals', function (Blueprint $table) {
            $table->integer('concert_id')->unsigned()->nullable();
        });

        Schema::table('rehearsals', function (Blueprint $table) {
            $table->foreign('concert_id')->references('id')->on('concerts')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rehearsals', function (Blueprint $table) {
            $table->dropForeign('rehearsals_concert_id_foreign');
        });

        Schema::table('rehearsals', function (Blueprint $table) {
            $table->dropColumn('concert_id');
        });
    }
}
