<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;

    protected $table = 'qr';
    protected $primaryKey = 'id_qr';

    protected $fillable = [
        'nama_kegiatan',
        'kode_qr',
        'expired_at',
        'cek_in',
        'cek_out',
        'cek_out_jumat',
        'cek_in_minggu',
    ];

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_qr', 'id_qr');
    }
}
