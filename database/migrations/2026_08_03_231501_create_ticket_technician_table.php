<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_technician', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('technician_id')->constrained()->cascadeOnDelete();
            $table->string('assignment_type')->default('assigned'); // 'self' or 'assigned'
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamps();

            $table->unique(['ticket_id', 'technician_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_technician');
    }
};
