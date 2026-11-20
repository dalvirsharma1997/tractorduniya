<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rate_limit_hits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_hash');
            $table->string('route');
            $table->unsignedInteger('hit_count')->default(1);
            $table->timestamp('window_started_at');

            $table->unique(['ip_hash', 'route', 'window_started_at'], 'rate_limit_hits_window_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rate_limit_hits');
    }
};
