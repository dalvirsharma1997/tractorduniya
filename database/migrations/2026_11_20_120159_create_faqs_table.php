<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->enum('faqable_type', ['tractor', 'implement', 'brand', 'page'])->nullable();
            $table->unsignedBigInteger('faqable_id')->nullable();
            $table->string('question');
            $table->text('answer');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['faqable_type', 'faqable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
