<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tractors', function (Blueprint $table) {
            $table->text('description')->nullable()->after('warranty_text');
        });
    }

    public function down(): void
    {
        Schema::table('tractors', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
