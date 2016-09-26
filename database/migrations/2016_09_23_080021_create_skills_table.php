<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->increments('id');
            $table->string('skill')->nullable();
            $table->timestamps();
        });

        Schema::create('advert_skill', function (Blueprint $table) {
            $table->integer('advert_id')->unsigned()->index();
            $table->foreign('advert_id')->references('id')->on('adverts')->onDelete('cascade');

            $table->integer('skill_id')->unsigned()->index();
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');

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
        Schema::drop('advert_skill');
        Schema::drop('skills');
    }
}
