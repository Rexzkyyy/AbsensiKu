<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magang';
    protected $primaryKey = 'id_magang';

    protected $fillable = [
        'id_user',
        'nama_lengkap',
        'instansi',
        'posisi_magang',
        'pembimbing',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
