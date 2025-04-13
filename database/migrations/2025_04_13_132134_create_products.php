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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Đây nên là `unsignedBigInteger`
            $table->string('Name');
            $table->text('Description');
            $table->decimal('Price', 10, 2);
            $table->integer('Stock');
            $table->integer('so_luong_da_ban')->default(0);
            $table->foreignId('CategoryId')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
