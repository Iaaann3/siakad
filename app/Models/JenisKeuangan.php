<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKeuangan extends Model
{
    protected $table = 'jenis_keuangan';
    protected $fillable = ['nama', 'deskripsi', 'kategori'];

    public function keuangan()
    {
        return $this->hasMany(Keuangan::class, 'id_jenis_keuangan');
    }
}
