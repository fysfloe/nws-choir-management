<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcertVoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concert_voice', function (Blueprint $table) {
            $table->integer('concert_id')->unsigned();
            $table->integer('voice_id')->unsigned();
            $table->integer('number')->default(0);
            $table->timestamps();

            $table->primary(['concert_id', 'voice_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concert_voice');
    }
}
