<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAddressColumnsCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('route')->nullable()->after('google_address');
            $table->string('postal_code')->nullable()->after('house_number');
            $table->string('country')->nullable()->after('region');

            $table->dropColumn('google_address');
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
            $table->string('google_address')->nullable()->after('address');

            $table->dropColumn('route');
            $table->dropColumn('postal_code');
            $table->dropColumn('country');
        });
    }
}
