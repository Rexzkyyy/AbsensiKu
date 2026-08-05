<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $primaryKey = 'id_absensi';

    protected $fillable = [
        'id_qr',
        'id_user',
        'absen_cek_in',
        'absen_cek_out',
        'status_cek_out',
        'status_cek_in',
        'hari_absen',
        'total_waktu',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function qr()
    {
        return $this->belongsTo(Qr::class, 'id_qr', 'id_qr');
    }
}
