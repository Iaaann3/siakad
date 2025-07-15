<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     *
     */
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('tingkat', 10)->nullable()->after('id'); // Atau after 'nomor_kelas' jika kamu ingin posisi berbeda
        });
    }

    /**
     *
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('tingkat');
        });
    }
};
