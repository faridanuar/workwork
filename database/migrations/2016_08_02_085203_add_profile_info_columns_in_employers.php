<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileInfoColumnsInEmployers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->string('business_name');
            $table->string('business_category');
            $table->string('business_contact');
            $table->string('business_website');
            $table->string('location');
            $table->string('street');
            $table->string('city');
            $table->string('zip');
            $table->string('state');
            $table->string('company_intro');
            $table->string('business_logo');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employers', function (Blueprint $table) {
            $table->dropColumn('business_name');
            $table->dropColumn('business_category');
            $table->dropColumn('business_contact');
            $table->dropColumn('business_website');
            $table->dropColumn('location');
            $table->dropColumn('street');
            $table->dropColumn('city');
            $table->dropColumn('zip');
            $table->dropColumn('state');
            $table->dropColumn('company_intro');
            $table->dropColumn('business_logo');
        });
    }
}
