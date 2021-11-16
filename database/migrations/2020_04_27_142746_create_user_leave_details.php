<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLeaveDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_leave_details', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->dateTime('from_date');
            $table->dateTime('to_date');
            $table->integer('category')->nullable();
            $table->string('reason')->nullable();
            $table->string('leave_type')->nullable();
            $table->integer('user_id');
            $table->timestamps();
        });

        Schema::create('leave_category', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->integer('total_leave')->nullable();
            $table->timestamps();
        });

        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('office_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_leave_details');
        Schema::dropIfExists('leave_category');
        Schema::dropIfExists('offices');
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('office_id');
        });
    }
}
