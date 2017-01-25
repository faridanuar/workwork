<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActivitiesAndApplicationsTableConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('description', 3000)->change();
        });


        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign('applications_advert_id_foreign');
            $table->dropForeign('applications_employer_id_foreign');
            $table->renameColumn('responded', 'employer_responded');
            $table->renameColumn('viewed', 'job_seeker_viewed_notification');
            $table->string('advert_job_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->string('description')->change();
        });


        Schema::table('applications', function (Blueprint $table) {
            $table->foreign('advert_id')->references('id')->on('adverts');
            $table->foreign('employer_id')->references('id')->on('employers');
            $table->renameColumn('employer_responded', 'responded');
            $table->renameColumn('job_seeker_viewed_notification', 'viewed');
            $table->dropColumn('advert_job_title');
        });
    }
}
