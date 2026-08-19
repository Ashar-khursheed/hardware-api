<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_filter_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('slug');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->unique(['category_id', 'slug']);
        });

        Schema::create('category_filter_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_filter_group_id');
            $table->string('value');
            $table->string('slug');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('category_filter_group_id', 'cfv_group_fk')
                ->references('id')
                ->on('category_filter_groups')
                ->onDelete('cascade');
            $table->unique(['category_filter_group_id', 'slug'], 'cfv_group_slug_unique');
        });

        Schema::create('product_category_filter_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_filter_value_id');
            $table->timestamps();

            $table->foreign('product_id', 'pcfv_product_fk')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->foreign('category_filter_value_id', 'pcfv_value_fk')
                ->references('id')
                ->on('category_filter_values')
                ->onDelete('cascade');
            $table->unique(['product_id', 'category_filter_value_id'], 'pcfv_product_value_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_category_filter_values');
        Schema::dropIfExists('category_filter_values');
        Schema::dropIfExists('category_filter_groups');
    }
};
