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
        Schema::create('correctives', function (Blueprint $table) {

            $table->id();

            /*
            |--------------------------------------------------------------------------
            | Report Information
            |--------------------------------------------------------------------------
            */

            $table->date('repair_date');

            $table->string('response_time')->nullable();

            $table->string('room')->nullable();

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
            | Service Report
            |--------------------------------------------------------------------------
            */

            $table->json('service_type')->nullable();

            $table->json('inspection')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Repair Information
            |--------------------------------------------------------------------------
            */

            $table->text('problem')->nullable();

            $table->text('solution')->nullable();

            $table->string('sparepart')->nullable();

            $table->integer('quantity')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Inspection Result
            |--------------------------------------------------------------------------
            */

            $table->string('inspection_result')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Technician
            |--------------------------------------------------------------------------
            */

            $table->json('technician')->nullable();

            /*
            |--------------------------------------------------------------------------
            | User Confirmation
            |--------------------------------------------------------------------------
            */

            $table->string('user_name')->nullable();

            $table->string('position')->nullable();

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
        Schema::dropIfExists('correctives');
    }
};