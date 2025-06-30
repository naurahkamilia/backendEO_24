<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_ticket';

    protected $fillable = ['id_registration', 'qr_code', 'status_hadir'];


    public function registration()
    {
    return $this->belongsTo(Registration::class, 'id_registration', 'id_registration');
    }

    public function attendance()
{
    return $this->hasOne(Attendance::class, 'id_ticket');
}

}
