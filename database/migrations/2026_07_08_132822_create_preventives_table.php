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
        Schema::create('preventives', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Report Information
            |--------------------------------------------------------------------------
            */

            $table->string('room');

            $table->date('schedule_date');

            /*
            |--------------------------------------------------------------------------
            | Asset Information
            |--------------------------------------------------------------------------
            */

            $table->string('asset_code')->nullable();

            $table->string('asset_name')->nullable();

            $table->string('brand')->nullable();

            $table->string('type')->nullable();

            $table->string('serial_number')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Asset Procurement Year
            |--------------------------------------------------------------------------
            */

            $table->date('procurement_year')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Technician
            |--------------------------------------------------------------------------
            */

            $table->string('technician')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            $table->enum('status', [

                'Scheduled',

                'Completed',

                'Missed',

            ])->default('Scheduled');

            /*
            |--------------------------------------------------------------------------
            | Notes
            |--------------------------------------------------------------------------
            */

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preventives');
    }
};