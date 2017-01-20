<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminTablesAndConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('activity')->nullable();
            $table->string('description')->nullable();
            $table->string('table')->nullable();
            $table->string('user')->nullable();
            $table->timestamps();
        });

        Schema::table('adverts', function (Blueprint $table) {
            $table->string('advert_from')->nullable();
            $table->string('logo_from')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('activities');

        Schema::table('adverts', function (Blueprint $table) {
            $table->dropColumn('advert_from');
            $table->dropColumn('logo_from');
        });
    }
}
