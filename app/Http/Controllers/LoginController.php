<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomRequest;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        try {
            return view('pages.login');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ], config('constan.message.validasi'));

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'username' => config('constan.message.validasi.kombinasi_auth'),
            'password' =>  config('constan.message.validasi.kombinasi_auth'),
        ]);
    }

    public function signOut(Request $request)
    {
        return redirect('login')->with(Auth::logout());
    }
}
