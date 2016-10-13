<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnInJobSeekersRatings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_seekers_ratings', function (Blueprint $table) {
            $table->integer('employer_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_seekers_ratings', function (Blueprint $table) {
            $table->integer('employer_id')->unsigned()->change();
        });
    }
}
