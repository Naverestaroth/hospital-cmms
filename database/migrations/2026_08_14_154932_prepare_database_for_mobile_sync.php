<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add SoftDeletes safely
        Schema::table('assets', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->softDeletes();
        });

        // 2. Add UUID to ticket_work_logs
        Schema::table('ticket_work_logs', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Populate UUID for existing data
        $logs = DB::table('ticket_work_logs')->whereNull('uuid')->get();
        foreach ($logs as $log) {
            DB::table('ticket_work_logs')->where('id', $log->id)->update(['uuid' => Str::uuid()->toString()]);
        }

        // Add Unique Constraint
        Schema::table('ticket_work_logs', function (Blueprint $table) {
            $table->unique('uuid');
        });

        // 3. Add UUID to ticket_activities
        Schema::table('ticket_activities', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Populate UUID for existing data
        $activities = DB::table('ticket_activities')->whereNull('uuid')->get();
        foreach ($activities as $activity) {
            DB::table('ticket_activities')->where('id', $activity->id)->update(['uuid' => Str::uuid()->toString()]);
        }

        // Add Unique Constraint
        Schema::table('ticket_activities', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('ticket_work_logs', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });

        Schema::table('ticket_activities', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
