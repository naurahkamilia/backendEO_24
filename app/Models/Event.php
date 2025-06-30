<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_event';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $hidden = []; 
    
    protected $fillable = [
        'id_event',
        'nama_event',
        'narasumber',
        'kategori_event',
        'jenis_event',
        'tanggal_event',
        'waktu_event',
        'lokasi',
        'deskripsi',
        'benefit',
        'catatan',
        'link_wa',
        'kuota',
        'gambar',
        'harga_event',
        'template_sertifikat', 
        'created_by'
    ];

    public function registrations()
    {
        return $this->hasMany(Registration::class, 'id_event');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
