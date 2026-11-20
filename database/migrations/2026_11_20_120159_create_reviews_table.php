<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->enum('reviewable_type', ['tractor', 'implement', 'brand']);
            $table->unsignedBigInteger('reviewable_id');
            $table->string('reviewer_name');
            $table->unsignedTinyInteger('rating');
            $table->string('title')->nullable();
            $table->text('body');
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->timestamps();

            $table->index(['reviewable_type', 'reviewable_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
