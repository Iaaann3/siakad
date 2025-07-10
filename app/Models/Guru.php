<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = ['id_users', 'id_mapel', 'nama', 'no_telepon', 'foto'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas');
    }

    public function mapel()
    {
        return $this->belongsTo(Mapel::class, 'id_mapel');
    }

    public function kelasWali()
    {
        return $this->hasOne(Kelas::class, 'wali_kelas');
    }
}
