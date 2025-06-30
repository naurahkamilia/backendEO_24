<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_attendance';

    protected $fillable = [
        'id_ticket',
        'status_attd'
    ];
    
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'id_ticket');
    }

    public function attendance() {
    return $this->hasOne(Attendance::class, 'id_ticket');
}

}
