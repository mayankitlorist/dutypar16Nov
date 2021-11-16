<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeviceTypeFirebaseTokenUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('firebase_token')->nullable();
            $table->string('device_type')->nullable();
        });


        Schema::table('leave_category', function (Blueprint $table) {
            $table->string('type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('firebase_token');
            $table->dropColumn('device_type');
        });
        Schema::table('leave_category', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
