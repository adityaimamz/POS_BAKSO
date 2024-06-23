<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index() {
        return view('auth.login');
    }

    public function store(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->role_id == '1') {
                return redirect('/superadmin');
            }
            if (Auth::user()->role_id == '2') {
                return redirect('/admin');
            }
            if (Auth::user()->role_id == '3') {
                return redirect('/kasir');
            }
            if (Auth::user()->role_id == '4') {
                return redirect()->back()->with('login', 'Fitur Outlet hanya tersedia di website versi 1.0++');
            }
            if (Auth::user()->role_id == '5') {
                return redirect('/waiters');
            }
        }

        return redirect()->route('login')->with('login', 'Email atau password salah.');
    }

    public function logout()
    {
        Session::flush();
        
        Auth::logout();

        return redirect('login');
    }

    public function change(Request $request) {
        if ($request->newpassword != $request->newpassword_confirmation) {
            return redirect()->back()->with('login', 'Konfirmasi Password Salah!');
        }

       if (User::where('id', $request->id)->update(['password' => Hash::make($request->newpassword)])) {
        return redirect()->back();
       };
    }
}