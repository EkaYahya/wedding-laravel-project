<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;  // Model tamu untuk menyimpan foto
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PhotoController extends Controller
{
    // Menampilkan halaman untuk mengambil foto
    public function index($guestSlug)
    {
        // Cari tamu berdasarkan slug
        $guest = Guest::where('slug', $guestSlug)->first();
        if (!$guest) {
            return redirect()->route('home')->with('error', 'Tamu tidak ditemukan!');
        }

        return view('photo.index', compact('guest')); // Mengirim guest ke view
    }

    // Menyimpan foto yang diambil
    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Menyimpan foto ke storage
        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('photos', $filename, 'public');

            // Menyimpan path foto ke database (misalnya di tabel guests)
            $guest = Guest::find($request->guest_id);  // Misalnya kita ambil guest_id dari form
            if ($guest) {
                $guest->photo = $path;
                $guest->save();
            }

            return redirect()->route('photo.index', ['guestSlug' => $guest->slug])->with('success', 'Foto berhasil disimpan!');
        }

        return back()->with('error', 'Gagal mengambil foto!');
    }

    // Menampilkan foto melalui API
    public function showPhoto($guestSlug)
    {
        $guest = Guest::where('slug', $guestSlug)->first();
        
        if (!$guest || !$guest->photo) { // pastikan ini mengakses 'photo', bukan 'photo_url'
            return response()->json(['message' => 'Foto tidak ditemukan'], 404);
        }
    
        return response()->json(['photo_url' => asset('storage/' . $guest->photo)]);
    }
}


