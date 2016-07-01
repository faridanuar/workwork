<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveColumnAppliactionAndNameFromJobSeekersAndAddColumnToTableApplications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_seekers', function (Blueprint $table) {
            $table->dropColumn('biodata');
            $table->dropColumn('name');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->text('introduction');
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
            $table->text('biodata');
            $table->string('name');
        });

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('introduction');
        });
    }
}
