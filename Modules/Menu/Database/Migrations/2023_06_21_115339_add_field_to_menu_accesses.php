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
        Schema::table('menu_accesses', function (Blueprint $table) {
            $table->integer('menu_access_create')->after('menu_access_access')->nullable();
            $table->integer('menu_access_read')->after('menu_access_create')->nullable();
            $table->integer('menu_access_update')->after('menu_access_read')->nullable();
            $table->integer('menu_access_delete')->after('menu_access_update')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_accesses', function (Blueprint $table) {
            $table->dropColumn('menu_access_create');
            $table->dropColumn('menu_access_read');
            $table->dropColumn('menu_access_update');
            $table->dropColumn('menu_access_delete');
        });
    }
};
