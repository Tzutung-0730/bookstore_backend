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
        Schema::table('books', function (Blueprint $table) {
            // 新增欄位
            $table->string('publisher')->nullable()->after('author'); // 出版社
            $table->date('publish_date')->nullable()->after('publisher'); // 出版日期
            $table->string('category')->nullable()->after('publish_date'); // 類別
            $table->string('isbn')->nullable()->unique()->after('category'); // ISBN，並設定為唯一
            $table->string('cover_image')->nullable()->after('description'); // 封面圖片
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            Schema::table('books', function (Blueprint $table) {
                // 回退新增的欄位
                $table->dropColumn('publisher');
                $table->dropColumn('publish_date');
                $table->dropColumn('category');
                $table->dropColumn('isbn');
                $table->dropColumn('cover_image');
            });
        });
    }
};
