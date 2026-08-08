<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('room')->nullable()->after('asset_id');
            $table->string('creator_type')->default('User')->after('reported_by'); // User / Technician
            $table->string('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->text('rejection_reason')->nullable()->after('approved_at');
            $table->text('cancellation_reason')->nullable()->after('rejection_reason');
        });

        // Change enum status column to string to support full hospital workflow status pipeline
        DB::statement("ALTER TABLE tickets MODIFY status VARCHAR(255) NOT NULL DEFAULT 'Waiting Approval'");
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'room',
                'creator_type',
                'approved_by',
                'approved_at',
                'rejection_reason',
                'cancellation_reason',
            ]);
        });
    }
};
