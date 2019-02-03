<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeadlineToSemestersProjectsConcertsAndRehearsalsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dateTime('deadline')->nullable();
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dateTime('deadline')->nullable();
        });

        Schema::table('concerts', function (Blueprint $table) {
            $table->dateTime('deadline')->nullable();
        });

        Schema::table('rehearsals', function (Blueprint $table) {
            $table->dateTime('deadline')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('semesters', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });

        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });

        Schema::table('concerts', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });

        Schema::table('rehearsals', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });
    }
}
