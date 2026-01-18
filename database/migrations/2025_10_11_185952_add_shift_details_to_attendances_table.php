<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    Schema::table('attendances', function (Blueprint $table) {
        $table->foreignId('shift_id')->nullable()->after('user_id')->constrained()->onDelete('set null');
        $table->string('status')->after('check_in_time')->default('Tepat Waktu');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            //
        });
    }
};
