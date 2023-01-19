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
        Schema::create(mtc::COMPANY_TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(mtc::COMPANY_TABLE_COMPANY_NAME, 100);
            $table->boolean(mtc::COMPANY_TABLE_STATUS)->default(mtc::DEFAULT_STATUE_VALUE);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(mtc::COMPANY_TABLE_NAME);
    }
};
