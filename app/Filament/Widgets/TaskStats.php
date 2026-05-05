<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth; // Jangan lupa import Auth
use Filament\Notifications\Notification; // 1. Tambahkan import Notification
use Carbon\Carbon;

class TaskStats extends BaseWidget
{
    // Mengatur agar widget ini tampil paling atas (opsional)
    protected static ?int $sort = 1;

    protected static ?string $pollingInterval = '1s'; // Update angka otomatis setiap 10 detik
    protected int | string | array $columnSpan = 'full'; // Agar memenuhi lebar layar

    // 3. Tambahkan fungsi mount() ini sebelum fungsi getStats()
    public function mount()
    {
        $userId = Auth::id();

        if ($userId) {
            // Cek tugas yang statusnya 'pending' dan tenggat waktunya hari ini atau sebelumnya
            $pendingTasks = Task::where('user_id', $userId)
                ->where('status', 'pending')
                ->whereDate('due_date', '<=', Carbon::today())
                ->count();

            // Jika ada tugas tertunda, munculkan notifikasi peringatan
            if ($pendingTasks > 0) {
                Notification::make()
                ->title('Perhatian!')
                ->body("Kamu memiliki {$pendingTasks} tugas yang tenggat waktunya hari ini atau sudah lewat.")
                ->warning() // Mengubah warna notifikasi menjadi kuning/oranye
                ->duration(8000) // Notifikasi tampil selama 8 detik
                ->send();
            }
        }
    }
    protected function getStats(): array
    {
        // Ambil ID user yang sedang login
        $userId = Auth::id();

        // Jika belum ada user yang login (berjaga-jaga), jangan tampilkan angka error
        if (!$userId) {
            return [];
        }

        return [
            // Kotak 1: Total Semua Tugas
            Stat::make('Total Tugas', Task::where('user_id', $userId)->count())
                ->description('Semua tugas yang pernah dibuat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            // Kotak 2: Tugas Belum Selesai (Pending)
            Stat::make('Tugas Pending', Task::where('user_id', $userId)->where('status', 'pending')->count())
                ->description('Tugas yang harus diselesaikan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]), // Trik UI: Menambahkan grafik statis agar terlihat keren

            // Kotak 3: Tugas Selesai (Completed)
            Stat::make('Tugas Selesai', Task::where('user_id', $userId)->where('status', 'completed')->count())
                ->description('Kerja bagus!')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([1, 3, 2, 5, 4, 6, 8, 10]), // Grafik yang seolah-olah naik
        ];
    }
}