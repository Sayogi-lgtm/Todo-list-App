<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens; 
use Filament\Models\Contracts\FilamentUser; // 1. Tambahkan ini
use Filament\Panel; // 2. Tambahkan ini
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser // 3. Tambahkan "implements FilamentUser"
{
    use HasApiTokens, Notifiable;
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // RELASI: Satu User memiliki banyak Task (One-to-Many)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Untuk sekarang, kita izinkan siapa saja yang berhasil login via Google
        return true; 
        
        // TIPS PRO: Jika ingin hanya emailmu saja, gunakan:
        // return str_ends_with($this->email, 'yogiwijanarko31@gmail.com');
    }
}
