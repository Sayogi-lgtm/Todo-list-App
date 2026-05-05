<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // Penting untuk fungsi auth()
use App\Models\User; // Penting agar Task kenal siapa User

class Task extends Model
{
    use HasFactory;

    // Kolom apa saja yang boleh diisi
    protected $fillable = [
        'user_id',
        'title',
        'notes',
        'is_important',
        'status',
        'due_date',
    ];

    // Pastikan tipe datanya otomatis di-convert (Casting) oleh Laravel
    protected $casts = [
        'is_important' => 'boolean',
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($task) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $task->user_id = \Illuminate\Support\Facades\Auth::id();
            }
        });
    }

    // RELASI: Task ini milik (belongs to) sebuah User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
}
