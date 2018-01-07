<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToConcertVoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concert_voice', function(Blueprint $table) {
            $table->foreign('concert_id')->references('id')->on('concerts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('voice_id')->references('id')->on('voices')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('concert_voice', function(Blueprint $table)
        {
            $table->dropForeign('concert_voice_concert_id_foreign');
            $table->dropForeign('concert_voice_voice_id_foreign');
        });
    }
}
