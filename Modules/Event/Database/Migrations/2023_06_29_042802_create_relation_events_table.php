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
        Schema::create('relation_events', function (Blueprint $table) {
            $table->uuid('relation_event_id', 32);
            $table->string('relation_event_user', 32);
            $table->string('relation_event_point', 32)->nullable();
            $table->string('relation_event_event', 32);
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
        Schema::dropIfExists('relation_events');
    }
};
