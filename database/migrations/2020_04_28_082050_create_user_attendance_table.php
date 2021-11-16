<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_attendance', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('face_status');
            $table->integer('location_status');
            $table->dateTime('intime')->nullable();
            $table->dateTime('outtime')->nullable();
            $table->string('category')->nullable();
            $table->string('reason')->nullable();
            $table->integer('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_attendance');
    }
}
