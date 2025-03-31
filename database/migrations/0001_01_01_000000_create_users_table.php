<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->id('UserId');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id('CategoryId');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id('ProductId');
            $table->string('Name');
            $table->text('Description');
            $table->decimal('Price', 10, 2);
            $table->integer('Stock');
            $table->integer('so_luong_da_ban')->default(0);
            $table->foreignId('CategoryId')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('discounts', function (Blueprint $table) {
            $table->id('DiscountId');
            $table->string('name');
            $table->decimal('percentage', 5, 2);
            $table->timestamps();
        });

        Schema::create('product_discounts', function (Blueprint $table) {
            $table->id('ProductDiscountId');
            $table->foreignId('ProductId')->constrained('products')->onDelete('cascade');
            $table->foreignId('DiscountId')->constrained('discounts')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id('InventoryId');
            $table->foreignId('ProductId')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });

        Schema::create('carts', function (Blueprint $table) {
            $table->id('CartId');
            $table->foreignId('UserId')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('CartItemId');
            $table->foreignId('CartId')->constrained('carts')->onDelete('cascade');
            $table->foreignId('ProductId')->constrained('products')->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderId');
            $table->foreignId('UserId')->constrained('users')->onDelete('cascade');
            $table->string('OrderStatus');
            $table->decimal('TotalAmount', 10, 2);
            $table->string('PaymentStatus');
            $table->text('ShippingAddress');
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id('OrderItemId');
            $table->foreignId('OrderId')->constrained('orders')->onDelete('cascade');
            $table->foreignId('ProductId')->constrained('products')->onDelete('cascade');
            $table->integer('Quantity');
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('TotalPrice', 10, 2);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id('PaymentId');
            $table->foreignId('OrderId')->constrained('orders')->onDelete('cascade');
            $table->string('PaymentMethod');
            $table->dateTime('PaymentDate');
            $table->decimal('Amount', 10, 2);
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id('ShipmentId');
            $table->foreignId('OrderId')->constrained('orders')->onDelete('cascade');
            $table->string('ShipmentStatus');
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id('ReviewId');
            $table->foreignId('UserId')->constrained('users')->onDelete('cascade');
            $table->foreignId('ProductId')->constrained('products')->onDelete('cascade');
            $table->text('Content');
            $table->integer('Rating');
            $table->timestamps();
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id('ImageId');
            $table->foreignId('ProductId')->constrained('products')->onDelete('cascade');
            $table->string('ImageUrl');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('product_images');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('product_discounts');
        Schema::dropIfExists('discounts');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('users');
    }
};
