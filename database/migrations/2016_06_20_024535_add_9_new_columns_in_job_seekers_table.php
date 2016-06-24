<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Add9NewColumnsInJobSeekersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_seekers', function (Blueprint $table) {
            $table->string('name');
            $table->integer('age')->unsigned();
            $table->string('contact');
            $table->string('location');
            $table->string('street');
            $table->string('city');
            $table->string('zip');
            $table->string('state');
            $table->string('country');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_seekers', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('age');
            $table->dropColumn('contact');
            $table->dropColumn('location');
            $table->dropColumn('street');
            $table->dropColumn('city');
            $table->dropColumn('zip');
            $table->dropColumn('state');
            $table->dropColumn('country');
        });
    }
}
