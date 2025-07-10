<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table    = 'kelas';
    protected $fillable = ['id_jurusan', 'nomor_kelas', 'kapasitas', 'wali_kelas'];

    public function wali()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'id_jurusan');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas');
    }
}
