<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContactInUsersTableAndRemoveContactFromJobSeekersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('contact');
        });

        Schema::table('job_seekers', function (Blueprint $table) {
            $table->dropColumn('contact');
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
            $table->dropColumn('contact');
        });

        Schema::table('job_seekers', function (Blueprint $table) {
            $table->string('contact');
        });
    }
}
