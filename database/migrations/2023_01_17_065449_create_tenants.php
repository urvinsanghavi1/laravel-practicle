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
        Schema::create(mtc::TENANT_TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(mtc::TENANT_TABLE_COMPANY_ID);
            $table->string(mtc::TENANT_TABLE_HOSTNAME, 10)->default(mtc::HOSTNAME_DEFAULT_VALUE);
            $table->integer(mtc::TENANT_TABLE_PORT)->default(mtc::PORT_DEFAULT_VALUE);
            $table->string(mtc::TENANT_TABLE_DBNAME);
            $table->string(mtc::TENANT_TABLE_DBUSERNAME);
            $table->string(mtc::TENANT_TABLE_DBPASSWORD);
            $table->timestamps();
            $table->foreign(mtc::TENANT_TABLE_COMPANY_ID)->references(mtc::COMPANY_TABLE_ID)->on(mtc::COMPANY_TABLE_NAME);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(mtc::TENANT_TABLE_NAME);
    }
};
