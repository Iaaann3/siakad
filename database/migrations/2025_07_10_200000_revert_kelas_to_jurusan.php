<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            if (! Schema::hasColumn('kelas', 'id_jurusan')) {
                $table->unsignedBigInteger('id_jurusan')->after('id');
                $table->foreign('id_jurusan')->references('id')->on('jurusan')->onDelete('cascade');
            }
            if (Schema::hasColumn('kelas', 'tingkat')) {
                $table->dropColumn('tingkat');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['id_jurusan']);
            $table->dropColumn('id_jurusan');
            $table->string('tingkat', 10)->nullable()->after('id');
        });
    }
};
