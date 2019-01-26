<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConfirmedExcusedToConcertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_concert', function (Blueprint $table) {
            $table->boolean('confirmed')->nullable();
            $table->boolean('excused')->nullable();
            $table->string('excuse')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_concert', function (Blueprint $table) {
            $table->dropColumn('confirmed');
            $table->dropColumn('excused');
            $table->dropColumn('excuse');
        });
    }
}
