<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectVoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_voice', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->integer('voice_id')->unsigned();
            $table->integer('number')->default(0);
            $table->timestamps();

            $table->primary(['project_id', 'voice_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_voice');
    }
}
