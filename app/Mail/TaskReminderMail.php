<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $task; // Variabel untuk menyimpan data tugas

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            // Ini akan menjadi Subjek/Judul Email kamu
            subject: 'Pengingat: Tugas "' . $this->task->title . '" Akan Segera Berakhir!',
        );
    }

    public function content(): Content
    {
        return new Content(
            // Ini merujuk ke file tampilan HTML email
            view: 'emails.task_reminder',
        );
    }
}