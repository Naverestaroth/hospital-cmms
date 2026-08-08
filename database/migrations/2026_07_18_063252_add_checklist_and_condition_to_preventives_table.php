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
        Schema::table('preventives', function (Blueprint $table) {
            $table->json('checklist')->nullable()->after('procurement_year');
            $table->string('condition')->nullable()->after('checklist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preventives', function (Blueprint $table) {
            $table->dropColumn(['checklist', 'condition']);
        });
    }
};
