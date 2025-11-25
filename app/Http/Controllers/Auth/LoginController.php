<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UserModel as User;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (! $user) {
            return back()->withErrors(['username' => 'Username tidak ditemukan'])->withInput();
        }

        if (Hash::check($request->password, $user->password)) {
            Auth::login($user);

            // role 1 → admin
            if ($user->idrole === '1') {
                return redirect('/admin/dashboard');
            }

            // role 2 → superadmin
            if ($user->idrole === '2') {
                return redirect('/superadmin/dashboard');
            }

            // jika role lain (opsional)
            return redirect('/dashboard');
        }

        return back()->withErrors(['password' => 'Password salah'])->withInput();
    }

        public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
