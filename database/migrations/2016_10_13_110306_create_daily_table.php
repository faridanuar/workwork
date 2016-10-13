<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('day')->nullable();
            $table->timestamps();
        });

        Schema::create('advert_daily', function (Blueprint $table) {
            $table->integer('advert_id')->unsigned()->index();
            $table->foreign('advert_id')->references('id')->on('adverts')->onDelete('cascade');

            $table->integer('day_id')->unsigned()->index();
            $table->foreign('day_id')->references('id')->on('daily_schedules')->onDelete('cascade');

            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();

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
        Schema::drop('advert_daily');
        Schema::drop('daily_schedules');
    }
}
