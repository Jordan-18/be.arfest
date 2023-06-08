<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('point_details', function (Blueprint $table) {
            $table->uuid('point_detail_id', 32);
            $table->string('point_detail_induk', 32);
            $table->string('point_detail_points', 128);
            $table->integer('point_detail_total', 32);
            $table->longText('point_detail_img');
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
        Schema::dropIfExists('point_details');
    }
};
