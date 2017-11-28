<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcertPieceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concert_piece', function (Blueprint $table) {
            $table->integer('concert_id')->unsigned();
            $table->integer('piece_id')->unsigned();
            $table->timestamps();

            $table->primary(['concert_id', 'piece_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concert_piece');
    }
}
