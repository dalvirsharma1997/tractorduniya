<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->enum('entity_type', ['tractor', 'implement', 'blog', 'news', 'page', 'category']);
            $table->unsignedBigInteger('entity_id');
            $table->char('language_code', 2);
            $table->string('field_name');
            $table->longText('field_value');
            $table->timestamps();

            $table->unique(['entity_type', 'entity_id', 'language_code', 'field_name'], 'translations_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
