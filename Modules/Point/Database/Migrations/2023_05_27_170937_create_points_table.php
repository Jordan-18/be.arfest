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
        Schema::create('points', function (Blueprint $table) {
            // $table->id();
            $table->uuid('point_id', 32);
            $table->string('point_user', 32);
            $table->string('point_jarak', 20);
            $table->string('point_jenis_busur', 20);
            $table->string('point_rambahan', 20);
            $table->string('point_jumlah_anak_panah', 20);
            $table->string('point_total', 32);
            $table->string('point_event', 32);
            $table->string('point_presentase', 16);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('points');
    }
};
