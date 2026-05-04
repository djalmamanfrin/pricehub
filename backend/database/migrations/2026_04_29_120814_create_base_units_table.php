<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('base_units', function (Blueprint $table) {
            $table->id();

            $table->string('name');        // mililitro, litro, grama, quilo
            $table->string('symbol');      // ml, l, g, kg
            $table->string('type');        // volume | weight
            $table->tinyInteger('multiplier'); // valor para conversao
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_units');
    }
};
