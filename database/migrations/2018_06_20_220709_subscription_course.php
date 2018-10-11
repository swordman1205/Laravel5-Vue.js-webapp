<?php

use App\Subscription;
use App\SubscriptionService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SubscriptionCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Subscription::create(array('name' => '12 Mesi'));
        Subscription::create(array('name' => '11 Mesi'));
        Subscription::create(array('name' => '10 Mesi'));
        Subscription::create(array('name' => '9 Mesi'));
        Subscription::create(array('name' => '8 Mesi'));
        Subscription::create(array('name' => '7 Mesi'));
        Subscription::create(array('name' => '6 Mesi'));
        Subscription::create(array('name' => '5 Mesi'));
        Subscription::create(array('name' => '4 Mesi'));
        Subscription::create(array('name' => '3 Mesi'));
        Subscription::create(array('name' => '2 Mesi'));
        Subscription::create(array('name' => '1 Mesi'));

        Schema::create('course_subscription', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('subscription_id');
            $table->float('price');
            $table->date('registration_deadline');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('subscription_services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        SubscriptionService::create(array('name' => 'Assicurazione'));
        SubscriptionService::create(array('name' => 'Borsone'));

        Schema::create('course_subscription_service', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('course_id');
            $table->unsignedInteger('subscription_service_id');
            $table->boolean('is_excluded');
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('subscription_service_id')->references('id')->on('subscription_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_subscription');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('course_subscription_service');
        Schema::dropIfExists('subscription_services');
    }
}
