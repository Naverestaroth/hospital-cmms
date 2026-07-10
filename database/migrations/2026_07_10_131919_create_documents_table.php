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
        Schema::create('documents', function (Blueprint $table) {

            $table->id();

            $table->string('document_code')->unique();

            $table->string('title');

            $table->foreignId('asset_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->enum('document_type', [
                'SOP',
                'Calibration Certificate',
                'User Manual',
                'Service Manual'
            ]);

            $table->string('revision')->nullable();

            $table->date('issue_date');

            $table->date('expiry_date')->nullable();

            $table->string('file_path')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
