<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecificShedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specific_schedules', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('advert_id')->unsigned();
            $table->string('start_date',50)->nullable();
            $table->string('end_date',50)->nullable();
            $table->string('start_time',50)->nullable();
            $table->string('end_time',50)->nullable();
            $table->timestamps();

            $table->foreign('advert_id')->references('id')->on('adverts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('specific_schedules');
    }
}
