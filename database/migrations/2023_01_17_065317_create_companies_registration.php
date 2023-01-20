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
        Schema::create(mainTableConstans::COMPANY_TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string(mainTableConstans::COMPANY_TABLE_COMPANY_NAME, 100);
            $table->boolean(mainTableConstans::COMPANY_TABLE_STATUS)->default(mainTableConstans::DEFAULT_STATUE_VALUE);
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
        Schema::dropIfExists(mainTableConstans::COMPANY_TABLE_NAME);
    }
};
