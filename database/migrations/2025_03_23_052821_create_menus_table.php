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
        Schema::create('menu', function (Blueprint $table) {
            $table->id('menu_id', true);       // public int menu
            $table->string('title');       // required string Title
            $table->string('url')->nullable();   // string? Url
            $table->string('icon')->nullable();  // string? Icon
            $table->integer('order')->default(0); // int Order
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
