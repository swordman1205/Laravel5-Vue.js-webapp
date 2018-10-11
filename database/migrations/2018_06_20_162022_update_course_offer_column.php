<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCourseOfferColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('offer');
            $table->boolean('has_trial_lesson')->default(false)->after('lesson_equipments');
            $table->boolean('has_subscriptions')->default(false)->after('has_trial_lesson');;
            $table->boolean('has_lesson_packages')->default(false)->after('has_subscriptions');;
            $table->float('membership_fee')->nullable()->after('has_lesson_packages');;
            $table->boolean('membership_fee_included')->nullable()->after('membership_fee');;
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
            $table->string('offer')->nullable();
            $table->dropColumn('has_trial_lesson');
            $table->dropColumn('has_subscriptions');
            $table->dropColumn('has_lesson_packages');
            $table->dropColumn('membership_fee');
            $table->dropColumn('membership_fee_included');
        });
    }
}
