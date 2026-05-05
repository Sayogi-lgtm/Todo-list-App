<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class SocialiteController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman login Google.
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Menangani respons dari Google setelah pengguna berhasil login.
     */
    public function callback()
    {
        try {
            // Menangkap data user dari Google
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah ada di database berdasarkan google_id atau email
            $user = User::updateOrCreate(
                ['google_id' => $googleUser->id], // Kriteria pencarian
                [
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'avatar' => $googleUser->avatar,
                    // Password tidak diisi karena menggunakan OAuth
                ]
            );

            // Mendaftarkan session login untuk user tersebut
            Auth::login($user);

            // Arahkan ke dashboard admin Filament
            return redirect()->intended('/admin');

        } catch (\Exception $e) {
            // Jika terjadi error (misal user membatalkan login), kembalikan ke halaman login
            return redirect('/admin/login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }
}