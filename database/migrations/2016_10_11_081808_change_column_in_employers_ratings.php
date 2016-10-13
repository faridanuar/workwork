<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeColumnInEmployersRatings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employers_ratings', function (Blueprint $table) {
            $table->integer('job_seeker_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employers_ratings', function (Blueprint $table) {
            $table->integer('job_seeker_id')->unsigned()->change();
        });
    }
}
