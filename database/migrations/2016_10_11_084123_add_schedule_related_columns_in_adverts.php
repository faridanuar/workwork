<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScheduleRelatedColumnsInAdverts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->string('schedule_type', 50)->nullable();
            $table->string('start_date', 50)->nullable();
            $table->string('end_date', 50)->nullable();
            $table->dropColumn('schedule_id');
            $table->dropColumn('skill');
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
            $table->dropColumn('schedule_type');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->integer('schedule_id')->unsigned();
            $table->string('skill')->nullable();
        });
    }
}
