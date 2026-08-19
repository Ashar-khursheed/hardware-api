<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'is_allow_all_zone')) {
                $table->boolean('is_allow_all_zone')->default(true)->after('commission_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'is_allow_all_zone')) {
                $table->dropColumn('is_allow_all_zone');
            }
        });
    }
};
