<?php

use App\Disability;
use App\Service;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DisabilitiesServicesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('disabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Disability::create(array('name' => 'Visiva'));
        Disability::create(array('name' => 'Uditiva'));
        Disability::create(array('name' => 'Motoria'));
        Disability::create(array('name' => 'Relazionale'));

        Schema::create('course_disability', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('disability_id');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('disability_id')->references('id')->on('disabilities')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Service::create(array('name' => 'Docce'));
        Service::create(array('name' => 'Spogliatoi'));
        Service::create(array('name' => 'Bar/ristorante'));
        Service::create(array('name' => 'Phon'));
        Service::create(array('name' => 'Wi-fi'));

        Schema::create('course_service', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('service_id');
            $table->timestamps();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('supported_disabilities');
            $table->dropColumn('supported_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('supported_disabilities')->nullable()->after('for_able_bodied');
            $table->string('supported_services')->nullable()->after('supported_disabilities');
        });
        Schema::dropIfExists('course_disability');
        Schema::dropIfExists('course_service');
        Schema::dropIfExists('disabilities');
        Schema::dropIfExists('services');
    }
}
