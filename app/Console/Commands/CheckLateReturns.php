<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Peminjaman;
use Carbon\Carbon;

class CheckLateReturns extends Command
{
    protected $signature = 'peminjaman:check-late';
    protected $description = 'Check for late returns and update their status';

    public function handle()
    {
        $peminjaman = Peminjaman::where('status', 'menunggu_pengembalian')
            ->where('tanggal_kembali', '<', Carbon::now())
            ->get();

        foreach ($peminjaman as $p) {
            $p->status = 'terlambat';
            $p->save();
        }

        $this->info('Late returns have been checked and updated.');
    }
} 