<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToUserConcertTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_concert', function(Blueprint $table)
        {
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
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
        Schema::table('user_concert', function(Blueprint $table)
        {
            $table->dropForeign('user_concert_user_id_foreign');
            $table->dropForeign('user_concert_concert_id_foreign');
            $table->dropForeign('user_concert_voice_id_foreign');
        });
    }
}
