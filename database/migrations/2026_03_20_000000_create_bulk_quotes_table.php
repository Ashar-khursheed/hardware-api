<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bulk_quotes', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('org_name')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('part_number');
            $table->string('quantity');
            $table->enum('urgency', ['asap', 'week', 'month', 'flexible'])->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'quoted', 'closed'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_quotes');
    }
};
