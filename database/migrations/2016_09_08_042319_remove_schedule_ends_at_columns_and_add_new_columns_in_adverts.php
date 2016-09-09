<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveScheduleEndsAtColumnsAndAddNewColumnsInAdverts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->dropColumn('schedule');
            $table->dropColumn('ends_at');
            $table->integer('schedule_id')->unsigned()->nullable();
            $table->string('current_plan', 50)->nullable();
            $table->timestamp('plan_ends_at')->nullable();
            $table->string('job_title')->nullable()->change();
            $table->string('description')->nullable()->change();
            $table->string('business_name')->nullable()->change();
            $table->string('location')->nullable()->change();
            $table->string('street')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('zip')->nullable()->change();
            $table->string('state')->nullable()->change();
            $table->string('country')->nullable()->change();
            $table->string('skill')->nullable()->change();
            $table->string('category')->nullable()->change();
            $table->string('rate')->nullable()->change();
            $table->string('oku_friendly')->nullable()->change();



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
            $table->dropColumn('schedule_id');
            $table->dropColumn('plan_ends_at');
            $table->dropColumn('current_plan');
            $table->text('schedule')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->string('job_title')->change();
            $table->string('description')->change();
            $table->string('business_name')->change();
            $table->string('location')->change();
            $table->string('street')->change();
            $table->string('city')->change();
            $table->string('zip')->change();
            $table->string('state')->change();
            $table->string('country')->change();
            $table->string('skill')->change();
            $table->string('category')->change();
            $table->string('rate')->change();
            $table->string('oku_friendly')->change();
        });
    }
}
