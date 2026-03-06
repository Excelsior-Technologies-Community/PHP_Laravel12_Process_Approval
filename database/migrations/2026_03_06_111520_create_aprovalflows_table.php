
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id');   // ADD THIS
            $table->string('name');
            $table->unsignedBigInteger('approver_id');
            $table->integer('step');
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('approval_requests')->cascadeOnDelete();
            $table->foreign('approver_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_flows');
    }
};

