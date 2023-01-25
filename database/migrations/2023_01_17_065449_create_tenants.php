<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Constants\MainTableConstans as mainTableConstans;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(mainTableConstans::TENANT_TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger(mainTableConstans::TENANT_TABLE_COMPANY_ID);
            $table->string(mainTableConstans::TENANT_TABLE_HOSTNAME, 10)->default(mainTableConstans::HOSTNAME_DEFAULT_VALUE);
            $table->integer(mainTableConstans::TENANT_TABLE_PORT)->default(mainTableConstans::PORT_DEFAULT_VALUE);
            $table->string(mainTableConstans::TENANT_TABLE_DBNAME);
            $table->string(mainTableConstans::TENANT_TABLE_DBUSERNAME);
            $table->string(mainTableConstans::TENANT_TABLE_DBPASSWORD);
            $table->string(mainTableConstans::TENANT_TABLE_DOMAIN_NAME, 30)->unique();
            $table->timestamps();
            $table->foreign(mainTableConstans::TENANT_TABLE_COMPANY_ID)->references(mainTableConstans::COMPANY_TABLE_ID)->on(mainTableConstans::COMPANY_TABLE_NAME);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(mainTableConstans::TENANT_TABLE_NAME);
    }
};
