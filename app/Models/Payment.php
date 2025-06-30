<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $fillable = [
        'id_users',
        'id_event',
        'bukti_pembayaran',
        'status',
        'tanggal_bayar',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }
}
