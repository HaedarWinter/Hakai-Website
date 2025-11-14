<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->string('karyawan_file', 255)->nullable()->after('tugas_file');
            $table->text('catatan')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropColumn(['karyawan_file', 'catatan']);
        });
    }
};
