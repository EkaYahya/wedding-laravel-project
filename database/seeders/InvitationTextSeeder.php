<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InvitationText;

class InvitationTextSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Judul dan Meta
            [
                'key' => 'title',
                'value' => 'Jack & Rose',
            ],
            [
                'key' => 'meta_keywords',
                'value' => 'Undangan Pernikahan, Jack & Rose',
            ],
            [
                'key' => 'meta_description',
                'value' => 'Undangan Pernikahan Jack & Rose',
            ],
            // Carousel
            [
                'key' => 'carousel_header',
                'value' => 'Dengan Hormat Kami Mengundang Saudara/i:',
            ],
            [
                'key' => 'carousel_subheader',
                'value' => 'Tamu Undangan',
            ],
            // About Section
            [
                'key' => 'about_section_title',
                'value' => 'About',
            ],
            [
                'key' => 'about_section_header',
                'value' => 'Groom & Bride',
            ],
            [
                'key' => 'the_groom_title',
                'value' => 'The Groom',
            ],
            [
                'key' => 'the_groom_description',
                'value' => 'Deskripsi tentang Groom.',
            ],
            [
                'key' => 'groom_name',
                'value' => 'Jack',
            ],
            [
                'key' => 'the_bride_title',
                'value' => 'The Bride',
            ],
            [
                'key' => 'the_bride_description',
                'value' => 'Deskripsi tentang Bride.',
            ],
            [
                'key' => 'bride_name',
                'value' => 'Rose',
            ],
            // Story Section
            [
                'key' => 'story_section_title',
                'value' => 'Story',
            ],
            [
                'key' => 'story_section_header',
                'value' => 'Our Love Story',
            ],
            [
                'key' => 'story_event_1_title',
                'value' => 'First Meet',
            ],
            [
                'key' => 'story_event_1_date',
                'value' => '01 Jan 2050',
            ],
            [
                'key' => 'story_event_1_description',
                'value' => 'Deskripsi tentang pertemuan pertama.',
            ],
            [
                'key' => 'story_event_2_title',
                'value' => 'First Date',
            ],
            [
                'key' => 'story_event_2_date',
                'value' => '05 Jan 2050',
            ],
            [
                'key' => 'story_event_2_description',
                'value' => 'Deskripsi tentang kencan pertama.',
            ],
            [
                'key' => 'story_event_3_title',
                'value' => 'Proposal',
            ],
            [
                'key' => 'story_event_3_date',
                'value' => '15 Feb 2050',
            ],
            [
                'key' => 'story_event_3_description',
                'value' => 'Deskripsi tentang proposal.',
            ],
            [
                'key' => 'story_event_4_title',
                'value' => 'Engagement',
            ],
            [
                'key' => 'story_event_4_date',
                'value' => '20 Feb 2050',
            ],
            [
                'key' => 'story_event_4_description',
                'value' => 'Deskripsi tentang pertunangan.',
            ],
            // Event Section
            [
                'key' => 'event_section_title',
                'value' => 'Event',
            ],
            [
                'key' => 'event_section_header',
                'value' => 'Our Wedding Event',
            ],
            [
                'key' => 'event_description',
                'value' => 'Deskripsi acara pernikahan.',
            ],
            [
                'key' => 'event_1_title',
                'value' => 'The Reception',
            ],
            [
                'key' => 'event_1_location',
                'value' => '123 Street, New York, USA',
            ],
            [
                'key' => 'event_1_time',
                'value' => '12:00PM - 13:00PM',
            ],
            [
                'key' => 'event_2_title',
                'value' => 'Wedding Party',
            ],
            [
                'key' => 'event_2_location',
                'value' => '123 Street, New York, USA',
            ],
            [
                'key' => 'event_2_time',
                'value' => '14:00PM - 17:00PM',
            ],
            // RSVP Section
            [
                'key' => 'rsvp_section_title',
                'value' => 'Kehadiran',
            ],
            [
                'key' => 'rsvp_section_header',
                'value' => 'Kehadiran Anda adalah kebahagiaan kami',
            ],
            [
                'key' => 'rsvp_form_title',
                'value' => 'Konfirmasi Kehadiran',
            ],
            [
                'key' => 'rsvp_attendance_label',
                'value' => 'Apakah Anda Akan Hadir?',
            ],
            [
                'key' => 'rsvp_attendance_yes',
                'value' => 'Ya',
            ],
            [
                'key' => 'rsvp_attendance_no',
                'value' => 'Tidak',
            ],
            [
                'key' => 'rsvp_guests_label',
                'value' => 'Berapa Orang Yang Bersama Anda?',
            ],
            [
                'key' => 'rsvp_submit_button',
                'value' => 'Konfirmasi Kehadiran',
            ],
            // Greeting Form
            [
                'key' => 'greeting_form_title',
                'value' => 'Kirim Ucapan Selamat',
            ],
            [
                'key' => 'greeting_label',
                'value' => 'Ucapan',
            ],
            [
                'key' => 'greeting_placeholder',
                'value' => 'Tulis ucapan selamat...',
            ],
            [
                'key' => 'greeting_submit_button',
                'value' => 'Kirim Ucapan',
            ],
            // QR Code
            [
                'key' => 'qr_code_title',
                'value' => 'Scan QR Code untuk Kehadiran',
            ],
            [
                'key' => 'qr_code_description',
                'value' => 'Silakan scan QR ini saat kedatangan untuk konfirmasi kehadiran Anda.',
            ],
            // Greetings Section
            [
                'key' => 'greetings_section_title',
                'value' => 'Ucapan dari Tamu Lain',
            ],
            // Footer
            [
                'key' => 'footer_title',
                'value' => 'Thank You',
            ],
            [
                'key' => 'footer_email',
                'value' => 'info@example.com',
            ],
            [
                'key' => 'footer_phone',
                'value' => '+012 345 6789',
            ],
            [
                'key' => 'footer_domain',
                'value' => 'Domain Name',
            ],
            [
                'key' => 'footer_designer',
                'value' => 'HTML Codex',
            ],
        ];

        foreach ($data as $item) {
            InvitationText::updateOrCreate(['key' => $item['key']], $item);
        }
    }
}
