<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;
use Exception;

class VerifyJWT
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['message' => 'Token tidak ditemukan'], 401);
        }

        try {
            $token = str_replace('Bearer ', '', $authHeader);
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET_KEY'), 'HS256'));

            $user = User::find($decoded->sub);
            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 401);
            }

            // Inject user ke request (seperti auth()->user())
            $request->merge(['user' => $user]);

            return $next($request);
        } catch (Exception $e) {
            return response()->json(['message' => 'Token tidak valid', 'error' => $e->getMessage()], 401);
        }
    }
}
