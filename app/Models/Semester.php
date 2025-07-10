<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table    = 'semester';
    protected $fillable = ['tahun_ajaran', 'tanggal_mulai', 'tanggal_selesai'];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_semester');
    }

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'id_semester');
    }
}
