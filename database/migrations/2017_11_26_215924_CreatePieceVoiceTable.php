<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePieceVoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('piece_voice', function (Blueprint $table) {
            $table->integer('piece_id')->unsigned();
            $table->integer('voice_id')->unsigned();
            $table->timestamps();

            $table->primary(['piece_id', 'voice_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('piece_voice');
    }
}
