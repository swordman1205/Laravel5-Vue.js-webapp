<?php

use App\LessonPackage;
use App\LessonPackageService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LessonPackagesCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->integer('n_lessons');
            $table->float('price');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('lesson_package_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        LessonPackageService::create(array('name' => 'Assicurazione'));
        LessonPackageService::create(array('name' => 'Borsone'));

        Schema::create('course_lesson_package_service', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('lesson_package_service_id');
            $table->boolean('is_excluded');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('lesson_package_service_id')->references('id')->on('lesson_package_services')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_lesson_package_service');
        Schema::dropIfExists('lesson_packages');
        Schema::dropIfExists('lesson_package_services');
    }
}
