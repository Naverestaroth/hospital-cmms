<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technician_schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technician_id')->constrained('technicians')->onDelete('cascade');
            $table->string('type'); // Lembur, Izin, Sakit, Cuti, Other
            $table->string('override_status'); // On Duty, Off Duty
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['technician_id', 'start_at', 'end_at'], 'tech_exc_id_start_end_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_schedule_exceptions');
    }
};
