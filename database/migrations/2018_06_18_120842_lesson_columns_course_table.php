<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LessonColumnsCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('lesson_during_course_time')->default(false)->after('offer');
            $table->dateTime('lesson_date_time')->nullable()->after('lesson_during_course_time');
            $table->string('lesson_price')->nullable()->after('lesson_date_time');
            $table->text('lesson_equipments')->nullable()->after('lesson_price');
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
            $table->dropColumn('lesson_during_course_time');
            $table->dropColumn('lesson_date_time');
            $table->dropColumn('lesson_price');
            $table->dropColumn('lesson_equipments');
        });
    }
}
