<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TablesColumnsAdjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('trial_used')->default(0);
            $table->integer('flagged_count')->unsigned();
        });

        Schema::table('adverts', function (Blueprint $table) {
            $table->dropColumn('business_name');
            $table->dropColumn('avatar');
            $table->renameColumn('trial_used', 'free_plan_used');
            $table->boolean('trial_used')->default(0)->change();
            $table->integer('sms_count')->unsigned();
            $table->boolean('about_to_expire')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('trial_used');
            $table->dropColumn('flagged_count');
        });

        Schema::table('adverts', function (Blueprint $table) {
            $table->string('business_name')->nullable();
            $table->string('avatar')->nullable();
            $table->renameColumn('free_plan_used', 'trial_used');
            $table->dropColumn('sms_count');
            $table->dropColumn('about_to_expire')->default(0);
        });
    }
}
