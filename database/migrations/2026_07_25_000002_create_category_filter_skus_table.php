<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_filter_skus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_filter_value_id');
            $table->string('sku');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->timestamps();

            $table->foreign('category_filter_value_id', 'cfs_value_fk')
                ->references('id')
                ->on('category_filter_values')
                ->onDelete('cascade');
            $table->foreign('product_id', 'cfs_product_fk')
                ->references('id')
                ->on('products')
                ->onDelete('set null');

            $table->unique(['category_filter_value_id', 'sku'], 'cfs_value_sku_unique');
            $table->index('sku');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_filter_skus');
    }
};
