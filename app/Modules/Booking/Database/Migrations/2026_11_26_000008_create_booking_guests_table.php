<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_guests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('guest_type');
            $table->unsignedInteger('guest_number');

            $table->timestamp('boarded_at')->nullable();

            $table->foreignId('checked_in_by')
                ->nullable()
                ->constrained('operators')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique([
                'booking_id',
                'guest_type',
                'guest_number',
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_guests');
    }
};
