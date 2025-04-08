<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('sessions');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 若需要回滾可以重新建立 sessions 資料表
        Schema::create('sessions', function (Blueprint $table) {
            $table->id('session_id');
            $table->unsignedBigInteger('user_id');
            $table->string('session_token');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('expires_at')->nullable()->default(null);
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });
    }
};
