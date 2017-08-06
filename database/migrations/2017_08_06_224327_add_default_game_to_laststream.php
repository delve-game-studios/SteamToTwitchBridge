<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultGameToLaststream extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('last_streams', function (Blueprint $table) {
            $table->dropColumn('last_game');
        });

        Schema::table('last_streams', function (Blueprint $table) {
            $table->string('last_game')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('last_streams', function (Blueprint $table) {
            //
        });
    }
}
