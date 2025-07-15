<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Jadwal;

class Mapel extends Model
{
    protected $table    = 'mapel';
    protected $fillable = ['nama_mapel'];
    public function jadwal()
{
    return $this->hasMany(Jadwal::class, 'id_mapel');
}

}
