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
        Schema::table('cart', function (Blueprint $table) {
            $table->foreign(['user_id'], 'cart_ibfk_1')->references(['user_id'])->on('users')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['book_id'], 'cart_ibfk_2')->references(['book_id'])->on('books')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart', function (Blueprint $table) {
            $table->dropForeign('cart_ibfk_1');
            $table->dropForeign('cart_ibfk_2');
        });
    }
};
