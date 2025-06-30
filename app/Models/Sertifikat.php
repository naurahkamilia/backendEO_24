<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sertifikat extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_sertifikat';
    protected $fillable = [
        'id_users',      
        'id_event',      
        'sertifikat_path',
        'kode_verifikasi',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class, 'id_registration');
    }
}
