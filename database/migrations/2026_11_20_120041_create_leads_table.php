<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->foreignId('state_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->string('city')->nullable();
            $table->foreignId('interested_tractor_id')->nullable()->constrained('tractors')->nullOnDelete();
            $table->foreignId('interested_implement_id')->nullable()->constrained('implements')->nullOnDelete();
            $table->string('source')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_source')->nullable();
            $table->enum('status', ['new', 'contacted', 'quoted', 'won', 'lost'])->default('new');
            $table->foreignId('assigned_dealer_id')->nullable()->constrained('dealers')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['status']);
            $table->index(['phone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
