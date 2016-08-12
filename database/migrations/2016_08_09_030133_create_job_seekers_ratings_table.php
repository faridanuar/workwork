<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobSeekersRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_seekers_ratings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employer_id');
            $table->unsignedInteger('job_seeker_id');
            $table->integer('rating');
            $table->string('comment');
            $table->string('postedBy');
            $table->timestamps();

            $table->foreign('employer_id')
                ->references('id')->on('employers');

            $table->foreign('job_seeker_id')
                ->references('id')->on('job_seekers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('job_seekers_ratings');
    }
}
