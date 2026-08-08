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
            $table->text('good_condition')->nullable()->after('procurement_year');
            $table->text('problem_found')->nullable()->after('good_condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('preventives', function (Blueprint $table) {
            $table->dropColumn(['good_condition', 'problem_found']);
        });
    }
};
