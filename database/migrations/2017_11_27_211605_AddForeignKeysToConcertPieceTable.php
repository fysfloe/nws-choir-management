<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToConcertPieceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('concert_piece', function(Blueprint $table)
        {
            $table->foreign('concert_id')->references('id')->on('concerts')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('piece_id')->references('id')->on('pieces')
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
        Schema::table('concert_piece', function(Blueprint $table)
        {
            $table->dropForeign('concert_piece_concert_id_foreign');
            $table->dropForeign('concert_piece_piece_id_foreign');
        });
    }
}
