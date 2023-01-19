<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\MainTableConstans as mtc;
use Illuminate\Support\Facades\Session;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = session('connection');
        if($connection){
            Schema::connection($connection)->create(mtc::COMPANY_PROFILE_TABLE, function (Blueprint $table) {
                $table->id();
                $table->string(mtc::COMPANY_PROFILE_COMPANY_NAME, 100);
                $table->string(mtc::COMPANY_PROFILE_EMAIL, 100);
                $table->char(mtc::COMPANY_PROFILE_PASSWORD, 6);
                $table->string(mtc::COMPANY_PROFILE_WEBSITE);
                $table->char(mtc::COMPANY_PROFILE_LICENSE_NUMBER, 50);
                $table->text(mtc::COMPANY_PROFILE_ADDRESS, 500);
                $table->char(mtc::COMPANY_PROFILE_COUNTRY, 50);
                $table->char(mtc::COMPANY_PROFILE_STATE, 50);
                $table->char(mtc::COMPANY_PROFILE_CITY, 50);
                $table->timestamps(); 
            });
        }
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
