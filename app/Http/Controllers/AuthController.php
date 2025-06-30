<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $user = auth()->user();

        return response()->json([
            'message' => 'Login berhasil',
            'token' => 'Bearer ' . $token,
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peserta'
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => 'Bearer ' . $token,
            'user' => $user
        ], 201);
    }

    public function show($id)
{
    $user = User::with('registrations')->find($id);

    if (!$user) {
        return response()->json(['message' => 'User tidak ditemukan'], 404);
    }

    if ($user->foto) {
        $user->foto_url = asset('storage/foto/' . $user->foto);
    } else {
        $gender = strtolower(optional($user->registrations->first())->jenis_kelamin ?? '');

        if ($gender === 'laki-laki') {
            $user->foto_url = asset('storage/foto/default-male.jpg');
        } elseif ($gender === 'perempuan') {
            $user->foto_url = asset('storage/foto/default-female.jpg');
        } else {
            $user->foto_url = asset('storage/foto/default-neutral.jpg');
        }
    }

    return response()->json($user);
}

public function getUser(Request $request)
{
    $user = $request->user()->load(['registrations.event']);

    $registrations = $user->registrations->map(function ($reg) {
    return [
        'id_registration' => $reg->id, 
        'status_registrasi' => $reg->status_registrasi,
        'total_bayar' => $reg->event->harga_event ?? '',
        'event' => [
            'id' => $reg->event->id ?? '',
            'nama_event' => $reg->event->nama_event ?? '',
            'gambar' => $reg->event->gambar ?? '',
            'tanggal_event' => $reg->event->tanggal_event ?? ''
        ]
        ];
    });

    return response()->json(['user' => $user, 'registrations' => $registrations]);
}

public function updatePassword(Request $request)
{
    $request->validate([
        'password_lama' => 'required|string',
        'password_baru' => 'required|string|min:6|confirmed',
    ]);

    $user = auth()->user();

    if (!Hash::check($request->password_lama, $user->password)) {
        return response()->json([
            'message' => 'Password lama salah'
        ], 400);
    }

    $user->password = Hash::make($request->password_baru);
    $user->save();

    return response()->json([
        'message' => 'Password berhasil diperbarui'
    ]);
}

public function updateProfile(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user->nama = $validated['nama'];
    $user->email = $validated['email'];

    if ($request->hasFile('foto')) {
        if ($user->foto && Storage::exists('public/foto/' . $user->foto)) {
            Storage::delete('public/foto/' . $user->foto); // hapus lama
        }

        $file = $request->file('foto');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/foto', $filename);
        $user->foto = $filename;
    }

    $user->save();

    return response()->json([
        'message' => 'Profil berhasil diperbarui',
        'data' => $user
    ]);
}

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::parseToken());
            return response()->json(['message' => 'Logout berhasil']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token tidak valid atau sudah logout'], 401);
        }
    }

    public function showLoginForm()
{
    return view('auth.login'); 
}

public function loginWeb(Request $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            Auth::logout();
            return back()->with('error', 'Hanya admin yang dapat mengakses halaman ini.');
        }

        return back()->with('error', 'Email atau password salah!');
    }

public function logoutWeb(Request $request){
        Auth::logout();
        return redirect()->route('login');
    }
}
