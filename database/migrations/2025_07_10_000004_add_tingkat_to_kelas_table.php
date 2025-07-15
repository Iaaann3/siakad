<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            if (! Schema::hasColumn('kelas', 'tingkat')) {
                $table->string('tingkat', 10)->nullable()->after('id');
            }
            // Hapus penambahan id_jurusan di migration ini, cukup satu migration saja yang handle id_jurusan
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            if (Schema::hasColumn('kelas', 'tingkat')) {
                $table->dropColumn('tingkat');
            }
        });
    }
};
