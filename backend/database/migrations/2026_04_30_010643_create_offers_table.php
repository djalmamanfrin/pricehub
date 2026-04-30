<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('market_id')->constrained()->cascadeOnDelete();
            $table->integer('match_score')->nullable();

            $table->decimal('price', 10, 2);
            $table->timestamp('collected_at')->nullable();

            $table->timestamps();

            $table->index(['product_id', 'price']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
