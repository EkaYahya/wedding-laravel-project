<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Undangan extends Model
{
    use HasFactory;

    protected $table = 'undangan';

    protected $fillable = [
        'nama_title',
        'video',
        'nama_pasangan',
        'nama_laki2',
        'keterangan_laki2',
        'nama_prmp',
        'keterengan_prpmp',
        'nama_resepsi',
        'keterangan_resepsi',
        'tempat_resepsi',
        'jam_resepsi',
        'tanggal_resepsi',
        'foto_pria',
        'foto_prmp',
        'foto_akad',
        'foto_resepsi',
    ];
}
