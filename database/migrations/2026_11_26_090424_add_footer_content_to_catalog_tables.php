<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A longer, admin-editable rich-text block rendered as its own
     * section just before the footer, separate from the short
     * "description" shown near the top of each page.
     */
    public function up(): void
    {
        foreach (['brands', 'categories', 'implements', 'tractors'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->longText('footer_content')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (['brands', 'categories', 'implements', 'tractors'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('footer_content');
            });
        }
    }
};
