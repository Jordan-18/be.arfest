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
        Schema::create('events', function (Blueprint $table) {

            $table->uuid('event_id', 32);
            $table->string('event_name', 128);
            $table->string('event_img', 128)->nullable();
            $table->longText('event_description')->nullable();
            $table->string('event_status', 32)->nullable();
            $table->string('event_created_by', 32)->nullable();
            $table->string('event_updated_by', 32)->nullable();

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
        Schema::dropIfExists('events');
    }
};
