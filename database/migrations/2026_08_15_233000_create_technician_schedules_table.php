<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technician_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('technician_id')->constrained('technicians')->onDelete('cascade');
            $table->string('shift_name')->nullable();
            $table->date('shift_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->index(['technician_id', 'shift_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_schedules');
    }
};
