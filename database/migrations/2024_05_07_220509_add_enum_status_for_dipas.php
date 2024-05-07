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
        Schema::table('dipas', function (Blueprint $table) {
            // $table->enum('status', [
            //     'draft',
            //     'wait-kp', 'reject-kp',
            //     'wait-ppk', 'reject-ppk',
            //     'wait-spi', 'reject-spi',
            //     'wait-perencanaan', 'reject-perencanaan',
            //     'accept', 'reject',
            //     'release'
            // ])->default('draft')->after('year');

            $table->enum('status', [
                'draft',
                'wait-kp', 'reject-kp',
                'wait-ppk', 'reject-ppk',
                'wait-spi', 'reject-spi',
                'wait-perencanaan', 'reject-perencanaan',
                'accept', 'reject',
                'release'
            ])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dipas', function (Blueprint $table) {
            // Revert the 'status' column to its previous state
            // $table->enum('status', [
            //     'draft',
            //     'wait-kp', 'reject-kp',
            //     'wait-ppk', 'reject-ppk',
            //     'wait-spi', 'reject-spi',
            //     'wait-perencanaan', 'reject-perencanaan',
            //     'accept', 'reject'
            // ])->default('draft')->change();
        });
    }
};
