<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('used_listings', function (Blueprint $table) {
            $table->id();
            $table->enum('listing_type', ['tractor', 'implement']);
            $table->foreignId('tractor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('implement_id')->nullable()->constrained()->nullOnDelete();
            $table->string('seller_name');
            $table->string('seller_phone');
            $table->string('city');
            $table->foreignId('state_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('year_of_purchase')->nullable();
            $table->unsignedInteger('hours_used')->nullable();
            $table->enum('condition', ['excellent', 'good', 'fair'])->default('good');
            $table->decimal('asking_price', 12, 2);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'sold'])->default('pending');
            $table->string('rejected_reason')->nullable();
            $table->timestamps();

            $table->index(['status', 'listing_type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('used_listings');
    }
};
