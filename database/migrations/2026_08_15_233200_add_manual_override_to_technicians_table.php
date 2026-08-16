<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->string('manual_override')->nullable()->after('duty_status');
        });
    }

    public function down(): void
    {
        Schema::table('technicians', function (Blueprint $table) {
            $table->dropColumn('manual_override');
        });
    }
};
