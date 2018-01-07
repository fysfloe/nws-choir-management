<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserSemesterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_semester', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('semester_id')->unsigned();
            $table->boolean('accepted')->nullable();
            $table->timestamps();

            $table->primary(['user_id', 'semester_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_semester');
    }
}
