<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogleAddressColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('google_address')->nullable()->after('address');
            $table->string('house_number')->nullable()->after('address');
            $table->string('municipality')->nullable()->after('house_number');
            $table->string('province')->nullable()->after('municipality');
            $table->string('region')->nullable()->after('province');
            $table->decimal('latitude', 10, 7)->nullable()->after('region');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
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
            $table->dropColumn('google_address');
            $table->dropColumn('municipality');
            $table->dropColumn('house_number');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
            $table->dropColumn('province');
            $table->dropColumn('region');
        });
    }
}
