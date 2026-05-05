<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\View\PanelsRenderHook; // Tambahkan ini di bagian atas
use Illuminate\Support\Facades\Blade; // Tambahkan ini juga

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
        
            ->login()
            // 1. Ganti nama aplikasi di pojok kiri atas dan halaman login
            ->brandName('Ruang Kerja Sayogi') 
            
            // 2. Ubah warna tombol utama (Bisa ganti Indigo menjadi Rose, Teal, Slate, dll)
            ->colors([
                'primary' => \Filament\Support\Colors\Color::Indigo,
            ])
            
            // 3. JURUS RAHASIA: Menyisipkan CSS untuk Background Login Estetik
            // JURUS PRESISI: Transparansi Total dengan Kotak Kaca Tunggal
            // JURUS PRESISI: Efek Kaca Transparan dengan Warna Teks Sempurna
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE,
                fn (): string => Blade::render('<style>
                    /* 1. Background dasar */
                    html, body {
                        background-image: url("https://images.unsplash.com/photo-1557682250-33bd709cbe85?q=80&w=2000&auto=format&fit=crop") !important;
                        background-size: cover !important;
                        background-position: center !important;
                        background-attachment: fixed !important;
                    }
                    
                    /* 2. Hilangkan background putih bawaan pada container utama saja */
                    .fi-simple-main-ctn, .fi-simple-main, .fi-simple-layout {
                        background: transparent !important;
                        background-color: transparent !important;
                        border: none !important;
                        box-shadow: none !important;
                    }

                    /* 3. Munculkan kembali kotak kaca pada elemen form-nya */
                    main section {
                        background: rgba(255, 255, 255, 0.25) !important;
                        backdrop-filter: blur(20px) !important; 
                        -webkit-backdrop-filter: blur(20px) !important;
                        border-radius: 2rem !important;
                        border: 1px solid rgba(255, 255, 255, 0.4) !important;
                        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2) !important;
                        padding: 2rem !important;
                    }

                    /* 4. Pastikan teks label, judul, dan remember me terbaca jelas */
                    h1, label, p, .fi-checkbox-label {
                        color: #ffffff !important;
                        text-shadow: 0px 2px 4px rgba(0,0,0,0.3);
                    }
                    
                    /* Input field agar tetap terlihat kontras */
                    input {
                        background: rgba(255, 255, 255, 0.9) !important;
                        color: #1f2937 !important;
                    }
                    
                    /* 5. PERBAIKAN TOMBOL: Kembalikan teks tombol Google ke warna gelap */
                    a span, a div, button:not([type="submit"]) span {
                        color: #1f2937 !important; /* Warna abu-abu gelap kehitaman */
                        text-shadow: none !important; /* Hilangkan bayangan teks agar rapi */
                    }

                    /* Pastikan teks di tombol utama "Sign in" tetap putih murni */
                    button[type="submit"] span, button[type="submit"] {
                        color: #ffffff !important;
                        text-shadow: none !important;
                    }
                </style>'),
            )
            // ... (biarkan sisa kode bawaannya di bawah sini seperti ->discoverResources, dll)
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                PanelsRenderHook::AUTH_LOGIN_FORM_AFTER,
                fn (): string => Blade::render('@include("auth.google-login-btn")')
            );
    }
}
