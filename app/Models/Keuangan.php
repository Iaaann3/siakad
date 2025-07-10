<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keuangan extends Model
{
    protected $table    = 'keuangan';
    protected $fillable = ['id_siswa', 'id_jenis_keuangan', 'id_semester', 'tanggal_bayar', 'jumlah_bayar', 'metode_pembayaran', 'status'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa');
    }

    public function jenisKeuangan()
    {
        return $this->belongsTo(JenisKeuangan::class, 'id_jenis_keuangan');
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class, 'id_semester');
    }
}
