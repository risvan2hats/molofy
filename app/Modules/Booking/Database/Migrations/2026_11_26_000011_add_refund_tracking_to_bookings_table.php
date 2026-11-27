<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('refund_status')->default('not_eligible');
            $table->timestamp('refund_processed_at')->nullable();
            $table->foreignId('refund_processed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });

        DB::table('bookings')
            ->where('status', 'cancelled')
            ->where('cancellation_refund_amount', '>', 0)
            ->update(['refund_status' => 'pending']);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['refund_processed_by']);
            $table->dropColumn([
                'refund_status',
                'refund_processed_at',
                'refund_processed_by',
            ]);
        });
    }
};