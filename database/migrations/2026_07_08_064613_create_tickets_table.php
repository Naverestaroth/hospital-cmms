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
        Schema::create('tickets', function (Blueprint $table) {

            $table->id();

            $table->string('ticket_code')->unique();

            $table->foreignId('asset_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('reported_by');

            $table->text('issue');

            $table->enum('priority', [
                'Low',
                'Medium',
                'High'
            ]);

            $table->enum('status', [
                'Waiting Approval',
                'Open',
                'Approved',
                'Assigned',
                'Accepted',
                'In Progress',
                'Waiting Sparepart',
                'Waiting Vendor',
                'Waiting User',
                'Repair Completed',
                'Waiting Corrective Report',
                'Corrective Report Completed',
                'Closed',
                'Rejected',
                'Cancelled',
                'Completed',
            ])->default('Waiting Approval');

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};