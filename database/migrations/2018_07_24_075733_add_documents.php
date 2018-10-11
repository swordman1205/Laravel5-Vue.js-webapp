<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('documents',function (Blueprint $table){
          $table->increments('id');
          $table->string('name');
          $table->timestamps();
      });

      Schema::create('course_document', function (Blueprint $table){
          $table->integer('course_id')->unsigned();
          $table->unsignedInteger('document_id');
          $table->foreign('course_id')->references('id')->on('courses');
          $table->foreign('document_id')->references('id')->on('documents');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_document');
        Schema::dropIfExists('documents');
    }
}
