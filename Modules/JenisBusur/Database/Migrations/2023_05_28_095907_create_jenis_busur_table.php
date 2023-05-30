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
        Schema::create('jenis_busurs', function (Blueprint $table) {
            $table->uuid('jenis_busur_id', 32);
            $table->uuid('jenis_busur_name', 32);
            $table->uuid('jenis_busur_kategori', 32);

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
        Schema::dropIfExists('jenis_busur');
    }
};
