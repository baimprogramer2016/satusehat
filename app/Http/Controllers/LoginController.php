<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomRequest;
use App\Repositories\Account\AccountInterface;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

    public function ubahAkun(AccountInterface $accountInterface, $username)
    {
        try {
            $data_account = $accountInterface;
            return view('pages.account.account', [
                "data_account" => $data_account->getDataAccountFirst($username)
            ]);
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }

    public function simpanAkun(Request $request, AccountInterface $accountInterface)
    {
        try {

            $param_akun = [
                "username" => $request->username,
                "name" => $request->name,
                "email" => $request->email,
            ];

            # check terleih dahulu pass lama apa sesuai dengan saat ini, jika tidak sama tolak
            if (!empty($request->pass_lama)) {
                if (!Hash::check($request->pass_lama, $request->user()->password)) {
                    return back()->withErrors([
                        'pass_lama' => ['Password Lama salah'],
                        "pass_value" => $request->pass_lama
                    ]);
                } else {
                    # jika sudah okeh, check pass baru apakah ada isinya / tidak kosong
                    if (!empty($request->pass_baru)) {
                        $param_akun['pass_baru'] = Hash::make($request->pass_baru);
                    }
                }
            }

            $accountInterface->updateAccont($param_akun);

            return back()
                ->with("pesan", config('constan.message.form.success_updated'))
                ->with('warna', 'success');
        } catch (Throwable $e) {
            return view("layouts.error", [
                "message" => $e
            ]);
        }
    }
}
