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
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropForeign(['id_jurusan']);
            $table->dropColumn('id_jurusan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jurusan')->nullable();
            $table->foreign('id_jurusan')->references('id')->on('jurusan');
        });
    }
};
