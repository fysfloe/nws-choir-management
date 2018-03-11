<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProjectToRehearsalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rehearsals', function (Blueprint $table) {
            $table->integer('project_id')->unsigned()->nullable();
        });

        Schema::table('rehearsals', function (Blueprint $table) {
            $table->foreign('project_id')->references('id')->on('projects')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rehearsals', function (Blueprint $table) {
            $table->dropForeign('rehearsals_project_id_foreign');
        });

        Schema::table('rehearsals', function (Blueprint $table) {
            $table->dropColumn('project_id');
        });
    }
}
