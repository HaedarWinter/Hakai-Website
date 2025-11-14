<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckSanctum extends Command
{
    protected $signature = 'check:sanctum';
    protected $description = 'Cek apakah Sanctum sudah terpasang dan aktif';

    public function handle()
    {
        $this->info("🔍 Mengecek Sanctum...");

        // 1. Cek package
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $sanctumInstalled = isset($composer['require']['laravel/sanctum']);

        $this->line($sanctumInstalled
            ? "✅ Sanctum sudah ada di composer.json"
            : "❌ Sanctum belum ada di composer.json"
        );

        // 2. Cek migrasi & tabel
        if (Schema::hasTable('personal_access_tokens')) {
            $this->line("✅ Tabel personal_access_tokens sudah ada di database");
        } else {
            $this->line("❌ Tabel personal_access_tokens belum ada (jalankan migrate)");
        }

        // 3. Cek model User pakai HasApiTokens
        $userFile = file_get_contents(app_path('Models/User.php'));
        $hasTrait = str_contains($userFile, 'HasApiTokens');

        $this->line($hasTrait
            ? "✅ User model sudah pakai HasApiTokens"
            : "❌ User model belum pakai HasApiTokens"
        );

        $this->info("Selesai cek Sanctum 🚀");
    }
}
