<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Nama primary key tabel.
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
        'keterangan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Disable timestamp update_at jika tidak diperlukan, tetapi karena migrasi kita menyediakannya, kita biarkan saja.
     */
    public $timestamps = true;

    /**
     * Relasi ke data Magang.
     */
    public function magang()
    {
        return $this->hasOne(Magang::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke data Absensi.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_user', 'id_user');
    }
}
