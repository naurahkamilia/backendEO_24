<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    // Jika kamu menggunakan primary key 'id_users'
    protected $primaryKey = 'id_users';

    protected $fillable = [
        'id_users',
        'nama',
        'email',
        'password',
        'role',
        'no_whatsapp',
        'foto'
    ];

    protected $appends = ['foto_url'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified' => 'datetime',
        'password' => 'hashed', // Laravel 10+
    ];

    public function registrations()
{
    return $this->hasMany(Registration::class, 'id_users');
}

    public function getFotoUrlAttribute()
    {
    return $this->foto
        ? asset('storage/foto/' . $this->foto)
        : asset('storage/foto/default-neutral.jpg');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
}
}
