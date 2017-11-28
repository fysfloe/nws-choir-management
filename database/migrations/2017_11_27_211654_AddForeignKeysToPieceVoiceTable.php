<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToPieceVoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('piece_voice', function(Blueprint $table)
        {
            $table->foreign('piece_id')->references('id')->on('pieces')
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
        Schema::table('piece_voice', function(Blueprint $table)
        {
            $table->dropForeign('piece_voice_piece_id_foreign');
            $table->dropForeign('piece_voice_voice_id_foreign');
        });
    }
}
