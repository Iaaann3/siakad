<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table    = 'siswa';
    protected $fillable = ['id_users', 'id_kelas', 'id_jurusan', 'nis', 'nama', 'alamat', 'jenis_kelamin', 'no_telepon', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'id_siswa');
    }
}
