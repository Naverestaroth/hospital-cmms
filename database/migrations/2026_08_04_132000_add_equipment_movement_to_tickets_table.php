<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->text('equipment_completeness')->nullable()->after('work_performed');
            $table->date('sent_to_workshop_date')->nullable()->after('equipment_completeness');
            $table->string('sent_by')->nullable()->after('sent_to_workshop_date');
            $table->string('received_by_workshop')->nullable()->after('sent_by');
            $table->date('returned_date')->nullable()->after('received_by_workshop');
            $table->string('returned_by')->nullable()->after('returned_date');
            $table->string('received_by_user')->nullable()->after('returned_by');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'equipment_completeness',
                'sent_to_workshop_date',
                'sent_by',
                'received_by_workshop',
                'returned_date',
                'returned_by',
                'received_by_user',
            ]);
        });
    }
};
