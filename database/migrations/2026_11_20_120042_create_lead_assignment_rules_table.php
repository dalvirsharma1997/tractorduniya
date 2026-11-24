<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_assignment_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('district_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('state_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('dealer_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('priority')->default(0);
            $table->timestamps();

            $table->index(['district_id', 'state_id', 'brand_id', 'priority'], 'lar_district_state_brand_priority_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_assignment_rules');
    }
};
