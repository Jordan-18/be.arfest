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
            $table->date('point_tanggal');
            $table->string('point_jarak', 20);
            $table->string('point_jenis_busur', 32);
            $table->integer('point_rambahan');
            $table->integer('point_jumlah_anak_panah');
            $table->integer('point_total')->nullable();
            $table->string('point_event', 32)->nullable();
            $table->integer('point_presentase')->nullable();
            $table->text('point_keterangan');
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
        Schema::dropIfExists('points');
    }
};
