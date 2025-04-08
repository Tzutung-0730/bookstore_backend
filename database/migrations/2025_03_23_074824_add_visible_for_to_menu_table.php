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
        Schema::table('menu', function (Blueprint $table) {
            // 新增 visible_for 欄位（可以根據你想的值來定義）
            $table->json('visible_for')->default(json_encode(['all']))->after('order');

            // 刪除原本的 menu_type 欄位（如果存在）
            if (Schema::hasColumn('menu', 'menu_type')) {
                $table->dropColumn('menu_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menu', function (Blueprint $table) {
            // 回復 menu_type 欄位（型別要跟原本一樣）
            $table->enum('menu_type', ['basic', 'user', 'admin'])
                  ->default('basic')
                  ->after('order');

            // 移除 visible_for 欄位
            $table->dropColumn('visible_for');
        });
    }
};
