<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToRehearsalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rehearsals', function(Blueprint $table)
        {
            $table->foreign('created_by')->references('id')->on('users')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rehearsals', function(Blueprint $table)
        {
            $table->dropForeign('rehearsals_created_by_foreign');
        });
    }
}
