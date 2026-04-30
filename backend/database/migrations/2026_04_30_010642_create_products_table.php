<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('unit_type_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name');
            $table->string('normalized_name')->index();
            $table->string('barcode')->nullable()->unique();
            $table->json('embedding')->nullable();
            $table->timestamps();

            $table->unique(['brand_id', 'category_id', 'unit_type_id', 'normalized_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
