<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trip_id')
                ->constrained('trips')
                ->restrictOnDelete();

            $table->foreignId('boat_id')
                ->constrained('boats')
                ->restrictOnDelete();

            $table->dateTime('departure_at');
            $table->dateTime('arrival_at')->nullable();

            $table->string('meeting_point');
            $table->text('meeting_instructions')->nullable();

            $table->string('status')->default('scheduled');

            $table->timestamps();

            $table->index(['departure_at', 'status']);
            $table->index(['trip_id', 'departure_at']);
            $table->index(['boat_id', 'departure_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};