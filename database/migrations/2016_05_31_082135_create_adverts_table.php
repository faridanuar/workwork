<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('job_title', 50);
            $table->integer('salary');
            $table->text('description');
            $table->string('business_name', 50);
            $table->string('business_logo');
            $table->string('location', 50);
            $table->string('street', 40);
            $table->string('city', 40);
            $table->string('zip', 10);
            $table->string('state', 40);
            $table->string('country');
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
        Schema::drop('adverts');
    }
}
