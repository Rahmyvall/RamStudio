<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
  public function showLoginForm(): View
  {
    return view('admin.auth.login');
  }

  public function login(Request $request): RedirectResponse
  {
    $credentials = $request->validate([
      'email' => ['required', 'string', 'email', 'max:190'],
      'password' => ['required', 'string'],
    ]);

    $remember = $request->boolean('remember');

    if (! Auth::attempt($credentials, $remember)) {
      return back()
        ->withInput($request->only('email'))
        ->withErrors(['email' => 'Email atau password salah.']);
    }

    $user = Auth::user();

    if (! $user->isAdmin()) {
      Auth::logout();
      $request->session()->invalidate();
      $request->session()->regenerateToken();

      return back()
        ->withInput($request->only('email'))
        ->withErrors(['email' => 'Akun ini tidak memiliki hak akses admin.']);
    }

    $request->session()->regenerate();

    return redirect()->intended(route('admin.dashboard'))
      ->with('success', 'Selamat datang kembali, ' . $user->name . '.');
  }

  public function logout(Request $request): RedirectResponse
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('admin.login')->with('success', 'Anda berhasil keluar.');
  }
}
