<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_seats', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained();
            $table->unsignedInteger('seat_id')->constrained();
            $table->unique(['user_id', 'seat_id'], 'UNIQUE_IDX_USER_SEAT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_seats');
    }
};
