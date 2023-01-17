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
        Schema::create(mtc::USER_TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->char(mtc::USER_TABLE_USER_NAME);
            $table->string(mtc::USER_TABLE_EMAIL)->unique();
            $table->string(mtc::USER_TABLE_PASSWORD);
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
        Schema::dropIfExists(mtc::USER_TABLE_NAME);
    }
};
