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
        if (!Schema::hasColumn('preventives', 'procurement_year')) {
            Schema::table('preventives', function (Blueprint $table) {
                $table->date('procurement_year')->nullable()->after('serial_number');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preventives', function (Blueprint $table) {
            $table->dropColumn('procurement_year');
        });
    }
};

