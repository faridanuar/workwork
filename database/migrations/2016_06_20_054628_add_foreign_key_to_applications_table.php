<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreign('job_seeker_id')
            ->references('id')->on('job_seekers')
            ->onDelete('cascade');

            $table->foreign('advert_id')
            ->references('id')->on('adverts')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign('applications_job_seeker_id_foreign');
            $table->dropForeign('applications_advert_id_foreign');
        });
    }
}
