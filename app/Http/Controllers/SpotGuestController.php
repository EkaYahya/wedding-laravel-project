<?php

namespace App\Http\Controllers;

use App\Models\SpotGuest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class SpotGuestController extends Controller
{
    // Menampilkan daftar tamu on-the-spot
    public function index(Request $request)
    {
        $query = $request->input('search', '');
    
        // Validasi input pencarian
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);
    
        // Query tamu berdasarkan input pencarian
        $spotGuests = SpotGuest::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'LIKE', '%' . $query . '%')
                         ->orWhere('phone_number', 'LIKE', '%' . $query . '%')
                         ->orWhere('guest_type', 'LIKE', '%' . $query . '%');
        })->paginate(10);
    
        // Data statistik tamu
        $totalSpotGuests = SpotGuest::count();
    
        return view('spot_guests.index', compact('spotGuests', 'totalSpotGuests'));
    }

    // Menampilkan form tambah tamu on-the-spot
    public function create()
    {
        return view('spot_guests.create');
    }

    // Menyimpan data tamu on-the-spot beserta foto
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'guest_type' => 'nullable|string',
                'custom_guest_type' => 'nullable|string',
                'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'name.required' => 'Nama tamu wajib diisi.',
                'name.string' => 'Nama tamu harus berupa teks.',
                'name.max' => 'Nama tamu tidak boleh lebih dari 255 karakter.',
                
                'phone_number.required' => 'Nomor WA wajib diisi.',
                'phone_number.string' => 'Nomor WA harus berupa teks.',
                'phone_number.max' => 'Nomor WA tidak boleh lebih dari 15 karakter.',
                
                'custom_guest_type.string' => 'Jenis tamu lainnya harus berupa teks.',
                
                'photo.required' => 'Foto selfie wajib diambil.',
                'photo.image' => 'File harus berupa gambar.',
                'photo.mimes' => 'Format foto harus jpeg, png, atau jpg.',
                'photo.max' => 'Ukuran foto maksimal 2MB.',
            ]);

            // Tentukan jenis tamu
            $guestType = !$request->guest_type ? $request->custom_guest_type : $request->guest_type;

            if (!$guestType) {
                return redirect()->back()->with('error', 'Jenis tamu lainnya harus diisi');
            }
            
            // Buat slug dari nama
            $slug = Str::slug($request->name);
            
            // Handle jika slug sudah ada
            $count = 1;
            $originalSlug = $slug;
            while (SpotGuest::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count;
                $count++;
            }
            
            // Upload foto
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = 'spot_guest_' . time() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('spot_guests', $photoName, 'public');
            }

            // Buat tamu on-the-spot baru
            SpotGuest::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'guest_type' => $guestType,
                'slug' => $slug,
                'photo' => $photoPath,
            ]);

            return redirect()->route('spot-guests.index')->with('success', 'Tamu on-the-spot berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Menampilkan detail tamu on-the-spot
    public function show($slug)
    {
        $spotGuest = SpotGuest::where('slug', $slug)->firstOrFail();
        return view('spot_guests.show', compact('spotGuest'));
    }

    // Form edit tamu on-the-spot
    public function edit($slug)
    {
        $spotGuest = SpotGuest::where('slug', $slug)->firstOrFail();
        return view('spot_guests.edit', compact('spotGuest'));
    }

    // Update data tamu on-the-spot
    public function update(Request $request, $slug)
    {
        $spotGuest = SpotGuest::where('slug', $slug)->firstOrFail();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'guest_type' => 'nullable|string',
            'custom_guest_type' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $guestType = $request->guest_type === null || $request->guest_type === '' ? $request->custom_guest_type : $request->guest_type;
        
        if (!$guestType) {
            return redirect()->back()->with('error', 'Jenis tamu lainnya harus diisi');
        }
        
        // Handle foto baru jika ada
        $photoPath = $spotGuest->photo;
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($spotGuest->photo && Storage::disk('public')->exists($spotGuest->photo)) {
                Storage::disk('public')->delete($spotGuest->photo);
            }
            
            // Upload foto baru
            $photo = $request->file('photo');
            $photoName = 'spot_guest_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('spot_guests', $photoName, 'public');
        }
        
        // Perbarui data tamu
        $spotGuest->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'guest_type' => $guestType,
            'slug' => Str::slug($request->name),
            'photo' => $photoPath,
        ]);

        return redirect()->route('spot-guests.index')->with('success', 'Data tamu on-the-spot berhasil diperbarui.');
    }

    // Hapus tamu on-the-spot
    public function destroy($slug)
    {
        $spotGuest = SpotGuest::where('slug', $slug)->firstOrFail();
        
        // Hapus foto jika ada
        if ($spotGuest->photo && Storage::disk('public')->exists($spotGuest->photo)) {
            Storage::disk('public')->delete($spotGuest->photo);
        }
        
        $spotGuest->delete();
        
        return redirect()->route('spot-guests.index')->with('success', 'Tamu on-the-spot berhasil dihapus.');
    }
    
    // Halaman selfie
    public function takeSelfie($slug = null)
    {
        // Jika belum ada slug, ini adalah halaman untuk tamu baru
        if (!$slug) {
            return view('spot_guests.selfie');
        }
        
        // Jika ada slug, ini adalah halaman untuk mengedit foto tamu
        $spotGuest = SpotGuest::where('slug', $slug)->firstOrFail();
        return view('spot_guests.selfie', compact('spotGuest'));
    }
    
    // Menyimpan hasil selfie
    // Pada metode storeSelfie di SpotGuestController
    // Menyimpan hasil selfie
    public function storeSelfie(Request $request)
    {
        try {
            // Jika data foto dikirim sebagai base64
            if ($request->has('photo_data')) {
                \Log::info('Menerima foto dari base64');
                
                // Validasi
                $request->validate([
                    'photo_data' => 'required',
                    'guest_id' => 'nullable|exists:spot_guests,id'
                ]);
                
                // Konversi base64 menjadi file
                $imageData = $request->input('photo_data');
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $decodedImage = base64_decode($imageData);
                
                // Simpan sebagai file sementara
                $tempFile = tempnam(sys_get_temp_dir(), 'selfie_');
                file_put_contents($tempFile, $decodedImage);
                
                // Buat UploadedFile dari file sementara
                $tempUploadedFile = new \Illuminate\Http\UploadedFile(
                    $tempFile,
                    'selfie.png',
                    'image/png',
                    null,
                    true
                );
                
                // Simpan menggunakan storeAs seperti fungsi store yang berhasil
                $filename = Str::random(20) . '.png';
                $path = $tempUploadedFile->storeAs('photos', $filename, 'public');
                
                // Update database jika guest_id ada
                if ($request->has('guest_id') && $request->guest_id) {
                    $spotGuest = SpotGuest::findOrFail($request->guest_id);
                    
                    // Hapus foto lama jika ada
                    if ($spotGuest->photo && Storage::disk('public')->exists($spotGuest->photo)) {
                        Storage::disk('public')->delete($spotGuest->photo);
                    }
                    
                    $spotGuest->update(['photo' => $path]);
                    
                    return response()->json([
                        'success' => true,
                        'message' => 'Foto berhasil diperbarui',
                        'photo_url' => asset('storage/' . $path)
                    ]);
                }
                
                // Hapus file sementara
                @unlink($tempFile);
                
                // Return success response
                return response()->json([
                    'success' => true,
                    'message' => 'Foto berhasil disimpan',
                    'photo_path' => $path,
                    'photo_url' => asset('storage/' . $path)
                ]);
            }
            // Jika foto dikirim sebagai file
            else if ($request->hasFile('photo')) {
                \Log::info('Menerima foto dari file upload');
                
                $request->validate([
                    'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'guest_id' => 'nullable|exists:spot_guests,id'
                ]);
                
                $image = $request->file('photo');
                $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs('photos', $filename, 'public');
                
                // Update database jika guest_id ada
                if ($request->has('guest_id') && $request->guest_id) {
                    $spotGuest = SpotGuest::findOrFail($request->guest_id);
                    
                    // Hapus foto lama jika ada
                    if ($spotGuest->photo && Storage::disk('public')->exists($spotGuest->photo)) {
                        Storage::disk('public')->delete($spotGuest->photo);
                    }
                    
                    $spotGuest->update(['photo' => $path]);
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Foto berhasil disimpan',
                    'photo_path' => $path,
                    'photo_url' => asset('storage/' . $path)
                ]);
            }
            else {
                \Log::error('Tidak ada foto yang diterima');
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada foto yang diterima'
                ], 400);
            }
        } catch (\Exception $e) {
            \Log::error('Error dalam menyimpan selfie: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
}
    
    // Export ke PDF
    public function exportPDF()
    {
        $spotGuests = SpotGuest::all();
        $pdf = PDF::loadView('exports.spot-guests-pdf', compact('spotGuests'));
        return $pdf->download('daftar-tamu-on-the-spot.pdf');
    }
}