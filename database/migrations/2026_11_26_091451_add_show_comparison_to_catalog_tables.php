<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-record toggle for whether the "Compare Tractors" section shows
     * on that brand/category/tractor's public page. Defaults to on.
     */
    public function up(): void
    {
        foreach (['brands', 'categories', 'tractors'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->boolean('show_comparison')->default(true);
            });
        }
    }

    public function down(): void
    {
        foreach (['brands', 'categories', 'tractors'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('show_comparison');
            });
        }
    }
};
