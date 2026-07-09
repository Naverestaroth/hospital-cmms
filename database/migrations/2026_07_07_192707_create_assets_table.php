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
        Schema::create('assets', function (Blueprint $table) {

            $table->id();

            $table->string('asset_code')->unique();

            $table->string('asset_name');

            $table->enum('category', [
                'Medical',
                'Non Medical'
            ]);

            $table->string('brand')->nullable();

            $table->string('model')->nullable();

            $table->string('serial_number')->nullable();

            $table->string('room');

            $table->date('purchase_date')->nullable();

            $table->enum('status', [
                'Active',
                'Maintenance',
                'Broken',
                'Retired'
            ])->default('Active');

            $table->text('description')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};