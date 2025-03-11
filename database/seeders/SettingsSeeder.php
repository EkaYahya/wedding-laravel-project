<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsSeeder extends Seeder
{
    public function run()
    {
        // Seeder untuk settings_events
        DB::table('settings_events')->insert([
            [
                'event_name' => 'Wedding of Astrid & Fitra',
                'user_name' => 'Astrid & Fitra',
                'event_date' => '2024-09-21',
                'invitation_count' => 370,
                'invitation_link' => 'https://undi.co.id/undangan/astrid-and-fitra',
                'image_url' => 'https://via.placeholder.com/250x150',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_name' => 'Birthday Party of John',
                'user_name' => 'John Doe',
                'event_date' => '2024-05-15',
                'invitation_count' => 50,
                'invitation_link' => 'https://example.com/invitation/john-birthday',
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Seeder untuk settings_wa_templates
        DB::table('settings_wa_templates')->insert([
            [
                'template_text' => 'Kepada Yth.\nBapak/Ibu/Saudara/i *[NAMA-TAMU]*\n-------------------------\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara kami.\n\n*Berikut link undangan kami*: [LINK]\n\nTerima kasih,\nAstrid & Fitra',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
