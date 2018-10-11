<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseServicesColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->boolean('for_able_bodied')->default(false)->after('responsible_photo');
            $table->string('supported_disabilities')->nullable()->after('for_able_bodied');
            $table->string('supported_services')->nullable()->after('supported_disabilities');
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
            $table->dropColumn('for_able_bodied');
            $table->dropColumn('supported_disabilities');
            $table->dropColumn('supported_services');
        });
    }
}
