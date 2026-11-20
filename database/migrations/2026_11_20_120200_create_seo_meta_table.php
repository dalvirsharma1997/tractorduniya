<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo_meta', function (Blueprint $table) {
            $table->id();
            $table->string('routable_type')->nullable();
            $table->unsignedBigInteger('routable_id')->nullable();
            $table->string('path')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();

            $table->index(['routable_type', 'routable_id']);
            $table->index(['path']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo_meta');
    }
};
