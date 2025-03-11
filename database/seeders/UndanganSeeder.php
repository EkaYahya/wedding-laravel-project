<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Undangan;
use Carbon\Carbon;

class UndanganSeeder extends Seeder
{
    public function run()
    {
        Undangan::create([
            'nama_title' => 'Jack & Rose Wedding Celebration',
            'video' => 'https://www.youtube.com/embed/example',
            'nama_pasangan' => 'Jack & Rose',
            'nama_laki2' => 'Jack Dawson',
            'keterangan_laki2' => 'Putra pertama dari Keluarga Dawson',
            'nama_prmp' => 'Rose DeWitt Bukater',
            'keterengan_prpmp' => 'Putri pertama dari Keluarga DeWitt Bukater',
            'nama_resepsi' => 'Resepsi Pernikahan Jack & Rose',
            'keterangan_resepsi' => 'Acara resepsi pernikahan akan diselenggarakan dengan penuh kebahagiaan',
            'tempat_resepsi' => 'Grand Ballroom Hotel Titanic',
            'jam_resepsi' => '19:00',
            'tanggal_resepsi' => Carbon::createFromDate(2025, 2, 14)
        ]);
    }
}