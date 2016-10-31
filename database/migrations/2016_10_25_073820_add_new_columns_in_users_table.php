<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('verified')->default(0);
            $table->string('verification_code')->nullable();
            $table->boolean('contact_verified')->default(0);
            $table->string('contact_verification_code')->nullable();
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
            $table->dropColumn('verified');
            $table->dropColumn('verification_code');
            $table->dropColumn('contact_verified');
            $table->dropColumn('contact_verification_code');
        });
    }
}
