<?php

use App\LegalForm;
use App\Typology;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CompanyColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('typology_id');
            $table->string('public_name')->nullable()->after('name');
            $table->unsignedInteger('legal_form_id')->nullable()->after('public_name');
            $table->string('email')->nullable()->after('legal_form_id');
            $table->string('phone_number')->nullable()->after('email');
            $table->string('fiscal_code')->nullable()->after('phone_number');
            $table->string('promotion_agency')->nullable()->after('fiscal_code');
            $table->string('logo_path')->nullable()->after('promotion_agency');
            $table->text('description')->nullable()->after('logo_path');
            $table->string('vat_number')->nullable()->after('description');
            $table->string('social_share')->nullable()->after('vat_number');
            $table->unsignedInteger('federation_id')->nullable()->after('social_share');
            $table->text('statute')->nullable()->after('federation_id');
            $table->text('statute_path')->nullable()->after('statute');
            $table->text('privacy_policy')->nullable()->after('statute');
            $table->text('privacy_policy_path')->nullable()->after('privacy_policy');
        });

        Schema::create('typologies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('legal_forms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedInteger('typology_id')->nullable()->after('privacy_policy');

            $table->foreign('typology_id')->references('id')->on('typologies');
            $table->foreign('federation_id')->references('id')->on('federations');
            $table->foreign('legal_form_id')->references('id')->on('legal_forms');
        });


        Schema::create('company_gallery_images', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
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
            $table->dropForeign('companies_typology_id_foreign');
            $table->dropForeign('companies_federation_id_foreign');
            $table->dropForeign('companies_legal_form_id_foreign');
            $table->dropColumn('public_name');
            $table->dropColumn('legal_form_id');
            $table->dropColumn('email');
            $table->dropColumn('phone_number');
            $table->dropColumn('fiscal_code');
            $table->dropColumn('promotion_agency');
            $table->dropColumn('logo_path');
            $table->dropColumn('description');
            $table->dropColumn('vat_number');
            $table->dropColumn('social_share');
            $table->dropColumn('federation_id');
            $table->dropColumn('statute');
            $table->dropColumn('statute_path');
            $table->dropColumn('privacy_policy');
            $table->dropColumn('privacy_policy_path');
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->integer('typology_id')->nullable()->after('name')->change();
        });
        Schema::dropIfExists('typologies');
        Schema::dropIfExists('legal_forms');
        Schema::dropIfExists('company_gallery_images');
    }
}
