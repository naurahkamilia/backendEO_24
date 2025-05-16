<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Register (API)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'in:admin,peserta' // optional, bisa dikunci ke peserta jika perlu
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'] ?? 'peserta'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'       => 'Registrasi berhasil.',
            'access_token'  => $token,
            'token_type'    => 'Bearer',
            'user'          => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role
            ]
        ], 201);
    }


    // Login (API)
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password salah!'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ]
        ]);
    }

    // Logout (API)
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Berhasil logout.'
        ]);
    }

    // =============================
    // ===== WEB AUTH SECTION ======
    // =============================

    // Tampilkan halaman login Web
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Login Web (Blade)
    public function loginWeb(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

             // Cek apakah role-nya admin
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            // Jika bukan admin, langsung logout dan tolak akses
            Auth::logout();
            return back()->with('error', 'Hanya admin yang dapat mengakses halaman ini.');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    // Logout Web
    public function logoutWeb(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }



}
