<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->integer('start_age')->nullable()->after('longitude');
            $table->integer('end_age')->nullable()->after('start_age');
            $table->string('level')->nullable()->after('end_age');
            $table->string('responsible_name')->nullable()->after('level');
            $table->string('responsible_email')->nullable()->after('responsible_name');
            $table->string('responsible_photo')->nullable()->after('responsible_email');
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
            $table->dropColumn('start_age');
            $table->dropColumn('end_age');
            $table->dropColumn('level');
            $table->dropColumn('responsible_name');
            $table->dropColumn('responsible_email');
            $table->dropColumn('responsible_photo');
        });
    }
}
