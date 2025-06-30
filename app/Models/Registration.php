<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_registration';
    protected $casts = ['hadir' => 'boolean'];

    protected $fillable = [
        'id_registration',
        'id_event',
        'id_users',
        'nama_lengkap',
        'no_whatsapp',
        'jenis_kelamin',
        'instansi',
        'participant',
        'status_bayar',
        'status_registrasi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    public function ticket() {
    return $this->hasOne(Ticket::class, 'id_registration', 'id_registration');
}



}
