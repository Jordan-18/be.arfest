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
        Schema::create('relation_user_events', function (Blueprint $table) {
            $table->id();
            $table->uuid('relation_id', 32);
            $table->string('user_id', 32);
            $table->string('point_id', 32)->nullable();
            $table->string('event_id', 32)->nullable();
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
        Schema::dropIfExists('relation_user_events');
    }
};
