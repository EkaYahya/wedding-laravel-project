<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;     // Model utk tabel settings_events
use App\Models\WATemplate;  // Model utk tabel settings_wa_templates
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Menampilkan semua event dan template WA di halaman Settings
     */
    public function index()
    {
        $settings_events = Setting::all();
        $waTemplate = WATemplate::first();  // Ambil template WA pertama

        return view('settings.index', compact('settings_events', 'waTemplate'));
    }

    /**
     * Update data Event (via modal "Edit Event")
     */
    public function updateEvent(Request $request)
    {
        $request->validate([
            'event_id'   => 'required|integer|exists:settings_events,id',
            'event_name' => 'required|string|max:255',
            'user_name'  => 'required|string|max:255',
            'event_date' => 'required|date',
            // invitation_count & invitation_link bisa ditambahkan sesuai form
        ]);

        $event = Setting::findOrFail($request->event_id);
        $event->event_name  = $request->event_name;
        $event->user_name   = $request->user_name;
        $event->event_date  = $request->event_date;
        $event->save();

        // Bisa pakai return redirect atau JSON response 
        return redirect()->route('settings.index')
            ->with('success', 'Data Event berhasil diperbarui!');
    }

    /**
     * Update Foto Event (via modal "Edit Photo")
     */
    public function updateImage(Request $request)
    {
        $request->validate([
            'image_event_id' => 'required|integer|exists:settings_events,id',
            'image_file'     => 'required|image|max:2048', // validasi tipe file
        ]);

        $event = Setting::findOrFail($request->image_event_id);

        // Optional: hapus file gambar lama jika ada
        // if ($event->image_url && Storage::disk('public')->exists(str_replace('/storage/', '', $event->image_url))) {
        //     Storage::disk('public')->delete(str_replace('/storage/', '', $event->image_url));
        // }

        // Upload baru
        $path = $request->file('image_file')->store('events', 'public');
        $event->image_url = '/storage/'.$path;
        $event->save();

        return redirect()->route('settings.index')
            ->with('success', 'Foto Event berhasil diperbarui!');
    }

    /**
     * Update Template WA (via modal "Edit Template WA")
     */
    public function updateWATemplate(Request $request)
    {
        $request->validate([
            'template_text' => 'required|string',
        ]);

        // Kalau di DB hanya ada 1 template, bisa pakai first() langsung
        $waTemplate = WATemplate::first();

        // Jika belum ada template di DB, buat baru
        if (!$waTemplate) {
            WATemplate::create([
                'template_text' => $request->template_text,
            ]);
        } else {
            // Update template existing
            $waTemplate->update([
                'template_text' => $request->template_text,
            ]);
        }

        return redirect()->route('settings.index')
            ->with('success', 'Template WA berhasil diperbarui!');
    }
}
