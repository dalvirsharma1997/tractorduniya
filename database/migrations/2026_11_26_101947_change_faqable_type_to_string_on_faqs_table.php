<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Widens faqable_type from a fixed enum (tractor/implement/brand/page)
     * to a plain string so it can also hold 'category' and 'dealer', using
     * raw SQL per driver to avoid doctrine/dbal's enum-column handling.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement('CREATE TABLE faqs_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                faqable_type VARCHAR NULL,
                faqable_id INTEGER UNSIGNED NULL,
                question VARCHAR NOT NULL,
                answer TEXT NOT NULL,
                sort_order INTEGER UNSIGNED NOT NULL DEFAULT 0,
                created_at DATETIME NULL,
                updated_at DATETIME NULL
            )');
            DB::statement('INSERT INTO faqs_new SELECT id, faqable_type, faqable_id, question, answer, sort_order, created_at, updated_at FROM faqs');
            DB::statement('DROP TABLE faqs');
            DB::statement('ALTER TABLE faqs_new RENAME TO faqs');
            DB::statement('CREATE INDEX faqs_faqable_type_faqable_id_index ON faqs (faqable_type, faqable_id)');

            return;
        }

        DB::statement('ALTER TABLE faqs MODIFY faqable_type VARCHAR(255) NULL');
    }

    public function down(): void
    {
        // Intentionally left as a widening-only migration; narrowing back to
        // an enum would fail if any 'category'/'dealer' rows exist.
    }
};
