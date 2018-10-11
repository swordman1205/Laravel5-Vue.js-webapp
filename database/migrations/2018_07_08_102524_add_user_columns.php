<?php

use App\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        $userRole = UserRole::create(array('name' => 'Amministratore'));

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'first_name');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('last_name')->nullable()->after('first_name');
            $table->unsignedInteger('user_role_id')->nullable()->after('last_name');

            $table->foreign('user_role_id')->references('id')->on('user_roles');
        });

        DB::table('users')->update(array('user_role_id' => $userRole->id));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_user_role_id_foreign');

            $table->renameColumn('first_name', 'name');
            $table->dropColumn('last_name');
            $table->dropColumn('user_role_id');
        });
        Schema::dropIfExists('user_roles');
    }
}
