<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tractors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('series')->nullable();
            $table->unsignedInteger('hp');
            $table->unsignedInteger('engine_cc')->nullable();
            $table->unsignedInteger('cylinders')->nullable();
            $table->unsignedInteger('fuel_tank_litres')->nullable();
            $table->string('transmission_type')->nullable();
            $table->string('gearbox')->nullable();
            $table->unsignedInteger('pto_hp')->nullable();
            $table->unsignedInteger('lifting_capacity_kg')->nullable();
            $table->enum('drive_type', ['2WD', '4WD'])->default('2WD');
            $table->string('warranty_text')->nullable();
            $table->unsignedSmallInteger('launch_year')->nullable();
            $table->enum('status', ['active', 'discontinued', 'upcoming'])->default('active');
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['hp']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tractors');
    }
};
