<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WATemplateSeeder extends Seeder
{
    public function run()
    {
        DB::table('settings_wa_templates')->insert([
            'template_text' => 'Kepada Yth.\nBapak/Ibu/Saudara/i *[NAMA-TAMU]*\n-------------------------\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i, teman sekaligus sahabat, untuk menghadiri acara pernikahan kami.\n\n*Berikut link undangan kami*, untuk info lengkap dari acara:\n[LINK]',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
