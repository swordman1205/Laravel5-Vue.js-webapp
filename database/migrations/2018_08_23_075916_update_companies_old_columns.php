<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCompaniesOldColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('website')->nullable();
            $table->string('facebook_account')->nullable();
            $table->string('affiliation')->nullable();
            $table->string('affiliation_code')->nullable();
            $table->string('discount_adhesion')->nullable();
            $table->string('trial_adhesion')->nullable();
            $table->string('import_batch')->nullable();
            $table->unsignedInteger('old_id')->nullable();
        });

        Schema::table('sports', function (Blueprint $table) {
            $table->string('slug')->nullable();
            $table->text('company_cover_image')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedInteger('old_id')->nullable();
            $table->string('import_batch')->nullable();
            $table->foreign('parent_id')->references('id')->on('sports')->onDelete('set null')->onUpdate('set null');
        });

        Schema::table('courses', function(Blueprint $table){
           $table->string('impianto')->nullable();
            $table->string('import_batch')->nullable();
            $table->unsignedInteger('old_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('website');
            $table->dropColumn('facebook_account');
            $table->dropColumn('affiliation');
            $table->dropColumn('affiliation_code');
            $table->dropColumn('discount_adhesion');
            $table->dropColumn('trial_adhesion');
            $table->dropColumn('import_batch');
            $table->dropColumn('old_id');
        });

        Schema::table('sports', function (Blueprint $table) {
            $table->dropColumn('slug');
            $table->dropColumn('company_cover_image');
            $table->dropColumn('parent_id');
            $table->dropColumn('old_id');
        });

        Schema::table('courses', function(Blueprint $table){
            $table->dropColumn('impianto');
        });
    }
}
