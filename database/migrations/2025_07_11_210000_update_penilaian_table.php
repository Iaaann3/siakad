<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            if (! Schema::hasColumn('penilaian', 'id_siswa')) {
                $table->foreignId('id_siswa')->constrained('siswa')->onDelete('cascade');
            }
            if (! Schema::hasColumn('penilaian', 'id_semester')) {
                $table->foreignId('id_semester')->constrained('semester')->onDelete('cascade');
            }
            if (! Schema::hasColumn('penilaian', 'jenis_penilaian')) {
                $table->enum('jenis_penilaian', ['harian', 'uts', 'uas']);
            }
            if (! Schema::hasColumn('penilaian', 'keterangan')) {
                $table->text('keterangan')->nullable();
            }
            if (Schema::hasColumn('penilaian', 'nilai')) {
                $table->decimal('nilai', 10, 2)->change();
            }
        });
    }
    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            if (Schema::hasColumn('penilaian', 'id_siswa')) {
                $table->dropForeign(['id_siswa']);
                $table->dropColumn('id_siswa');
            }
            if (Schema::hasColumn('penilaian', 'id_semester')) {
                $table->dropForeign(['id_semester']);
                $table->dropColumn('id_semester');
            }
            if (Schema::hasColumn('penilaian', 'jenis_penilaian')) {
                $table->dropColumn('jenis_penilaian');
            }
            if (Schema::hasColumn('penilaian', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
            if (Schema::hasColumn('penilaian', 'nilai')) {
                $table->decimal('nilai', 5, 0)->change();
            }
        });
    }
};
