<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->constrained('approval_requests')->onDelete('cascade');
            $table->string('name');
            $table->foreignId('approver_id')->constrained('users')->onDelete('cascade');
            $table->integer('step')->nullable(); // Add this line
            $table->string('status')->default('pending'); // Add this line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('approval_flows');
    }
};
