<?php

use App\Company;
use App\Course;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SlugColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses',function(Blueprint $table){
            $table->string('slug')->after("name")->nullable();
        });


        Course::whereNull('slug')->each(function($course){
            $slug = SlugService::createSlug(Course::class, 'slug', $course->name);
            $course->slug = $slug;
            $course->save();
        });

        Schema::table('companies',function(Blueprint $table){
            $table->string('slug')->after("name")->nullable();
        });


        Company::whereNull('slug')->each(function($company){
            $slug = SlugService::createSlug(Company::class, 'slug', $company->name);
            $company->slug = $slug;
            $company->save();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses',function(Blueprint $table){
            $table->dropColumn('slug');
        });
        Schema::table('company',function(Blueprint $table){
            $table->dropColumn('slug');
        });
    }
}
