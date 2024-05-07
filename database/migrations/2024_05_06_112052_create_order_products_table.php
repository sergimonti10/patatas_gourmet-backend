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
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_product')->nullable();
            $table->unsignedBigInteger('id_order')->nullable();
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->foreign('id_product')->references('id')->on('products')->onDelete('set null');
            $table->foreign('id_order')->references('id')->on('orders')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_products', function (Blueprint $table) {
            if (Schema::hasColumn('order_products', 'id_product')) {
                $table->dropForeign(['id_product']);
                $table->dropColumn('id_product');
            }
            if (Schema::hasColumn('order_products', 'id_order')) {
                $table->dropForeign(['id_order']);
                $table->dropColumn('id_order');
            }
        });
        Schema::dropIfExists('order_products');
    }
};
