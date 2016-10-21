<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDateStartAndEndColumnInAdverts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->renameColumn('start_date', 'daily_start_date');
            $table->renameColumn('end_date', 'daily_end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->renameColumn('daily_start_date', 'start_date');
            $table->renameColumn('daily_end_date', 'end_date');
        });
    }
}
