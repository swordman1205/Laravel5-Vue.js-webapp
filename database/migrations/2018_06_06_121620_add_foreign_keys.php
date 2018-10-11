<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('companies')->truncate();
        DB::table('company_sport')->truncate();
        DB::table('company_user')->truncate();

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedInteger('typology_id')->change();
            $table->unsignedInteger('sport_id')->change();
            $table->unsignedInteger('registrant_id')->change();

            $table->foreign('registrant_id')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('set null')->onUpdate('cascade');;
        });

        Schema::table('company_sport', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->change();
            $table->unsignedInteger('sport_id')->change();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');;
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade')->onUpdate('cascade');;
        });

        Schema::table('company_user', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->change();
            $table->unsignedInteger('user_id')->change();

            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
            $table->dropForeign('companies_registrant_id_foreign');
            $table->dropForeign('companies_sport_id_foreign');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->integer('typology_id')->change();
            $table->integer('sport_id')->change();
            $table->integer('registrant_id')->change();

        });

        Schema::table('company_sport', function (Blueprint $table) {
            $table->dropForeign('company_sport_company_id_foreign');
            $table->dropForeign('company_sport_sport_id_foreign');

        });
        Schema::table('company_sport', function (Blueprint $table) {
            $table->integer('company_id')->change();
            $table->integer('sport_id')->change();
        });

        Schema::table('company_user', function (Blueprint $table) {
            $table->dropForeign('company_user_company_id_foreign');
            $table->dropForeign('company_user_user_id_foreign');

        });

        Schema::table('company_user', function (Blueprint $table) {
            $table->integer('company_id')->change();
            $table->integer('user_id')->change();
        });
    }
}
