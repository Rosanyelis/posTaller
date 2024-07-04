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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('product_name');
            $table->string('product_code');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('net_unit_price', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('item_discount', 10, 2)->nullable();
            $table->integer('tax')->nullable();
            $table->decimal('item_tax', 10, 2)->nullable();
            $table->decimal('subtotal', 10, 2);
            $table->decimal('real_unit_price', 10, 2)->nullable();
            $table->decimal('cost', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotation_items');
    }
};
