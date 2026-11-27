<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departure_operator', function (Blueprint $table) {
            $table->id();

            $table->foreignId('departure_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('operator_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['departure_id', 'operator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departure_operator');
    }
};
