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
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('book_copy_id')->nullable()->after('book_id')->constrained('book_copies')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            try {
                $table->dropForeign(['book_copy_id']);
            } catch (\Exception $e) {}
            $table->dropColumn('book_copy_id');
        });
    }
};
