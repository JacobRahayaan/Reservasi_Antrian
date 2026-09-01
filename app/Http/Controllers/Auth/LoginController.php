<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Tampilkan form login. Satu halaman dipakai untuk dua peran
     * (Administrator & Customer Service), dipilih lewat dropdown.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi sesuai guard yang dipilih pada dropdown "Login
     * Sebagai". Kredensial disertai `is_active => true` agar akun yang
     * dinonaktifkan Admin (lewat modul Kelola Pengguna) otomatis ditolak
     * oleh Auth::attempt() tanpa perlu pengecekan manual terpisah.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $peran = $request->validated('peran');
        $ingatSaya = $request->boolean('ingat_saya');

        $kredensial = [
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'is_active' => true,
        ];

        if (Auth::guard($peran)->attempt($kredensial, $ingatSaya)) {
            $request->session()->regenerate();

            return redirect()->intended(
                $peran === 'admin' ? route('admin.dashboard') : route('cs.dashboard')
            );
        }

        return back()
            ->withInput($request->only('peran', 'email'))
            ->withErrors([
                'email' => 'Email, password, atau peran yang dipilih tidak sesuai.',
            ]);
    }

    /**
     * Proses logout. Mendeteksi guard mana yang sedang aktif (karena hanya
     * satu peran yang bisa login dalam satu sesi browser pada satu waktu).
     */
    public function destroy(): RedirectResponse
    {
        $guardAktif = Auth::guard('admin')->check() ? 'admin' : 'petugas';

        Auth::guard($guardAktif)->logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Tampilkan halaman "Lupa Password" — placeholder statis tanpa logic
     * pengiriman email, sesuai keputusan MVP (reset password via email
     * ditunda ke fase berikutnya).
     */
    public function lupaPassword(): View
    {
        return view('auth.lupa-password');
    }
}