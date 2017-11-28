<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserVoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_voice', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('voice_id')->unsigned();
            $table->timestamps();

            $table->primary(['user_id', 'voice_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_voice');
    }
}
