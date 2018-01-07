<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRehearsalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_rehearsal', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('rehearsal_id')->unsigned();
            $table->boolean('accepted')->nullable();
            $table->timestamps();

            $table->primary(['user_id', 'rehearsal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_rehearsal');
    }
}
