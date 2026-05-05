<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Mail\TaskReminderMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckDueTasks extends Command
{
    // Nama perintah yang akan kita jalankan di terminal
    protected $signature = 'tasks:send-reminders';

    protected $description = 'Mengecek tugas yang mendekati tenggat waktu dan mengirim email pengingat';

    public function handle()
    {
        // Cari semua tugas yang statusnya "pending" dan due_date-nya BESOK
        $tasks = Task::where('status', 'pending')
            ->whereDate('due_date', Carbon::tomorrow())
            ->with('user') // Pastikan mengambil data user untuk tau alamat emailnya
            ->get();

        $count = 0;

        foreach ($tasks as $task) {
            // Pastikan user-nya ada dan punya email
            if ($task->user && $task->user->email) {
                // Kirim email!
                Mail::to($task->user->email)->send(new TaskReminderMail($task));
                $count++;
            }
        }

        // Tampilkan pesan di terminal
        $this->info("Berhasil mengirim {$count} email pengingat untuk tugas besok!");
    }
}