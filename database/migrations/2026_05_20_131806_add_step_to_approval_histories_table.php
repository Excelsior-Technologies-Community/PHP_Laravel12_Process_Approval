<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('approval_histories', function (Blueprint $table) {
            $table->integer('step')->nullable();
        });
    }

    public function down()
    {
        Schema::table('approval_histories', function (Blueprint $table) {
            $table->dropColumn('step');
        });
    }
};