<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tractor_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tractor_id')->constrained()->cascadeOnDelete();
            $table->foreignId('state_id')->nullable()->constrained()->cascadeOnDelete();
            $table->decimal('price_min', 12, 2);
            $table->decimal('price_max', 12, 2);
            $table->decimal('on_road_price', 12, 2)->nullable();
            $table->date('effective_date');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['tractor_id', 'state_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tractor_prices');
    }
};
