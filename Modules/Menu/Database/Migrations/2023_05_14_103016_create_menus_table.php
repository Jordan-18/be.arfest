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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->uuid('menu_id', 32);
            $table->string('menu_kode', 64);
            $table->string('menu_name', 64);
            $table->string('menu_order', 64);
            $table->string('menu_parent', 64)->nullable();
            $table->string('menu_hassub', 64)->nullable();
            $table->string('menu_level', 64);
            $table->string('menu_icon', 64);
            $table->string('menu_endpoint', 64);
            $table->boolean('menu_status', 12)->default(1);
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
        Schema::dropIfExists('menus');
    }
};
