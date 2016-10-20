<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameDayIdColumnInAdvertDaily extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('advert_daily', function (Blueprint $table) {
            $table->renameColumn('day_id', 'daily_schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('advert_daily', function (Blueprint $table) {
            $table->renameColumn('daily_schedule_id', 'day_id');
        });
    }
}
