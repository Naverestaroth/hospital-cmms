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
        Schema::table('correctives', function (Blueprint $table) {
            if (!Schema::hasColumn('correctives', 'tanggal_instal')) {
                $table->date('tanggal_instal')->nullable()->after('serial_number');
            }
            if (!Schema::hasColumn('correctives', 'jam_laporan')) {
                $table->string('jam_laporan')->nullable()->after('repair_date');
            }
            if (!Schema::hasColumn('correctives', 'jam_visit')) {
                $table->string('jam_visit')->nullable()->after('jam_laporan');
            }
            if (!Schema::hasColumn('correctives', 'distributor')) {
                $table->string('distributor')->nullable()->after('tanggal_instal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('correctives', function (Blueprint $table) {
            $columns = [];
            if (Schema::hasColumn('correctives', 'tanggal_instal')) {
                $columns[] = 'tanggal_instal';
            }
            if (Schema::hasColumn('correctives', 'jam_laporan')) {
                $columns[] = 'jam_laporan';
            }
            if (Schema::hasColumn('correctives', 'jam_visit')) {
                $columns[] = 'jam_visit';
            }
            if (Schema::hasColumn('correctives', 'distributor')) {
                $columns[] = 'distributor';
            }
            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
