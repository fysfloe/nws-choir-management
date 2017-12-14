<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToUserRehearsalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_rehearsal', function(Blueprint $table)
        {
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('rehearsal_id')->references('id')->on('rehearsals')
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
        Schema::table('user_rehearsal', function(Blueprint $table)
        {
            $table->dropForeign('user_rehearsal_user_id_foreign');
            $table->dropForeign('user_rehearsal_rehearsal_id_foreign');
        });
    }
}
