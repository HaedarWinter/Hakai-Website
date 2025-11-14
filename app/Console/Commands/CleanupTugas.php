<?php

namespace App\Console\Commands;

use App\Models\Tugas;
use Illuminate\Console\Command;

class CleanupTugas extends Command
{
    protected $signature = 'tugas:cleanup';
    protected $description = 'Hapus tugas yang user-nya sudah tidak ada';

    public function handle()
    {
        $this->info('Memulai cleanup tugas...');

        $count = Tugas::whereDoesntHave('user')->count();

        if ($count > 0) {
            Tugas::whereDoesntHave('user')->delete();
            $this->info("Berhasil menghapus {$count} tugas yang user-nya tidak ada.");
        } else {
            $this->info('Tidak ada tugas yang perlu dibersihkan.');
        }

        return 0;
    }
}
