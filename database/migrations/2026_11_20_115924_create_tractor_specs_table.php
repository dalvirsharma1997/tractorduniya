<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tractor_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tractor_id')->constrained()->cascadeOnDelete();
            $table->string('spec_group')->default('general');
            $table->string('spec_key');
            $table->string('spec_value');
            $table->unsignedInteger('sort_order')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tractor_specs');
    }
};
