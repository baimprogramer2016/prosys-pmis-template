<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

use Throwable;
class LoginController extends Controller
{
    public function index(Request $request){
        try{
            return view('login');
        }catch (Throwable $e) {
         
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
        
    }
    public function prosesLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

 
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
 
            return redirect()->intended('dashboard');
        }
 
        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ])->onlyInput('username');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Anda telah logout.');
    }

    public function insertAdmin(){
      
        $user = new User();
    $user->name = "Administrator";
    $user->username = "admin";
    $user->email = "admin@mail.com";
    $user->password = bcrypt("admin-123"); // Menggunakan bcrypt untuk mengenkripsi password
    $user->save();

    return response()->json(['message' => 'User berhasil dibuat'], 201);
    }
}