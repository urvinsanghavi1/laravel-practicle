<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\MainTableConstans as mtc;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Log::info("IN DB | ".config("database.connections.tenant.database"));
        
        Schema::create(mtc::COMPANY_PROFILE_TABLE, function (Blueprint $table) {
            $table->unsignedBigInteger(mtc::COMPANY_PROFILE_COMPANY_ID);
            $table->string(mtc::COMPANY_PROFILE_EMAIL, 100);
            $table->char(mtc::COMPANY_PROFILE_PASSWORD, 6);
            $table->string(mtc::COMPANY_PROFILE_WEBSITE);
            $table->bigInteger(mtc::COMPANY_PROFILE_LICENSE_NUMBER, 50);
            $table->text(mtc::COMPANY_PROFILE_ADDRESS, 500);
            $table->char(mtc::COMPANY_PROFILE_COUNTRY, 50);
            $table->char(mtc::COMPANY_PROFILE_STATE, 50);
            $table->char(mtc::COMPANY_PROFILE_CITY, 50);
            $table->timestamps();
            $table->foreign(mtc::COMPANY_PROFILE_COMPANY_ID)->references(mtc::COMPANY_TABLE_ID)->on(mtc::COMPANY_TABLE_NAME);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(mtc::COMPANY_PROFILE_TABLE);
    }
};
