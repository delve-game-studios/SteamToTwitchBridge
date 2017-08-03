<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExpirationOfUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_expire')->default('Never')->index();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->string('default_expiration')->default('Never');
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
            $table->dropColumn('role_expire');
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('default_expiration');
        });
    }
}
