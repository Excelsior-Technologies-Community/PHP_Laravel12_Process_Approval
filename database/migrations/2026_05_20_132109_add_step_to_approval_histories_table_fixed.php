<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('approval_histories', function (Blueprint $table) {
            if (!Schema::hasColumn('approval_histories', 'step')) {
                $table->integer('step')->nullable()->after('approver_id');
            }
            if (!Schema::hasColumn('approval_histories', 'comment')) {
                $table->text('comment')->nullable()->after('status');
            }
        });
    }

    public function down()
    {
        Schema::table('approval_histories', function (Blueprint $table) {
            $table->dropColumn(['step', 'comment']);
        });
    }
};