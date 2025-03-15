<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Imports\GuestsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;     // Model utk tabel settings_events
use App\Models\Undangan;
use Illuminate\Support\Facades\Cache;


class GuestController extends Controller
{
    // Menampilkan daftar tamu
    public function index(Request $request)
    {
        $query = $request->input('search', ''); // Ambil input pencarian
        $settings_events = Setting::all(); // Data setting event
        $undangan = Undangan::all();

        // Validasi input pencarian untuk mencegah eksploitasi
        $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        // Query tamu berdasarkan input pencarian
        $guestsQuery = Guest::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'LIKE', '%' . $query . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $query . '%')
                        ->orWhere('guest_type', 'LIKE', '%' . $query . '%');
        });

        // Handle sorting
        if ($request->has('sort') && $request->has('direction')) {
            $guestsQuery->orderBy($request->input('sort'), $request->input('direction'));
        } else {
            $guestsQuery->orderBy('name', 'asc'); // Default sorting
        }

        $guests = $guestsQuery->paginate(10);

        // Data statistik tamu
        $totalGuests = Guest::count(); 
        $totalAttended = Guest::where('attended', true)->count(); 
        $totalNotAttended = $totalGuests - $totalAttended;

        // Check if AJAX request
        if ($request->ajax()) {
            return response()->json([
                'html' => view('guests.partials.guest-table', compact('guests'))->render(),
                'pagination' => view('guests.partials.pagination', compact('guests'))->render(),
            ]);
        }

        return view('guests.index', compact(
            'totalGuests',
            'totalAttended',
            'totalNotAttended',
            'guests',
            'settings_events',
            'undangan'
        ));
    }

    /**
     * AJAX endpoint for guests data
     */
    public function ajaxSearch(Request $request)
    {
        $query = $request->input('search', '');
        $sort = $request->input('sort', 'name');
        $direction = $request->input('direction', 'asc');
        
        // Validate sort field to prevent SQL injection
        $validSortFields = ['name', 'guest_type', 'created_at', 'updated_at', 'attended'];
        if (!in_array($sort, $validSortFields)) {
            $sort = 'name';
        }
        
        // Validate direction
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }
        
        // Query tamu berdasarkan input pencarian
        $guestsQuery = Guest::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'LIKE', '%' . $query . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $query . '%')
                        ->orWhere('guest_type', 'LIKE', '%' . $query . '%');
        });
        
        // Apply sorting
        $guestsQuery->orderBy($sort, $direction);
        
        // Get paginated results
        $guests = $guestsQuery->paginate(10);
        
        return response()->json([
            'current_page' => $guests->currentPage(),
            'data' => $guests->items(),
            'from' => $guests->firstItem(),
            'last_page' => $guests->lastPage(),
            'per_page' => $guests->perPage(),
            'to' => $guests->lastItem(),
            'total' => $guests->total(),
            'prev_page_url' => $guests->previousPageUrl(),
            'next_page_url' => $guests->nextPageUrl(),
        ]);
    }

    public function downloadQr($slug)
    {
        $guest = Guest::where('slug', $slug)->firstOrFail();
        
        // Buat direktori sementara jika belum ada
        $tempDir = storage_path('app/public/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        
        // Buat nama file unik
        $filename = 'qr-code-' . $guest->slug . '-' . time() . '.png';
        $filePath = $tempDir . '/' . $filename;
        
        // Generate QR code dan simpan sebagai file
        QrCode::format('png')
            ->size(300)
            ->margin(1)
            ->generate(route('guests.updateAttendance', $guest->slug), $filePath);
        
        // Download file dengan header yang benar
        return response()->download($filePath, $filename, [
            'Content-Type' => 'image/png',
        ])->deleteFileAfterSend(true); // Hapus file setelah diunduh
    }
    
    public function showDataTamu(Request $request)
    {
        $query = $request->input('search');
        $undangan = Undangan::all();
        $guests = Guest::when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->where('name', 'LIKE', '%' . $query . '%')
                        ->orWhere('phone_number', 'LIKE', '%' . $query . '%')
                        ->orWhere('guest_type', 'LIKE', '%' . $query . '%');
        })->paginate(10);

        $totalGuests = Guest::count(); 
        $totalAttended = Guest::where('will_attend', 1)->count(); 
        $totalNumberOfGuests = Guest::whereNotNull('number_of_guests')->sum('number_of_guests'); 

        return view('guests.guest', compact('totalGuests', 'totalAttended', 'totalNumberOfGuests', 'guests', 'undangan'));
    }

    // Menampilkan form tambah tamu
    public function create()
    {
        return view('guests.create');
    }

    // Menyimpan data tamu
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'phone_number' => 'required|string|max:15',
                'guest_type' => 'nullable|string',
                'custom_guest_type' => 'nullable|string',
            ], [
                'name.required' => 'Nama tamu wajib diisi.',
                'name.string' => 'Nama tamu harus berupa teks.',
                'name.max' => 'Nama tamu tidak boleh lebih dari 255 karakter.',
                
                'phone_number.required' => 'Nomor WA wajib diisi.',
                'phone_number.string' => 'Nomor WA harus berupa teks.',
                'phone_number.max' => 'Nomor WA tidak boleh lebih dari 15 karakter.',
                
                'custom_guest_type.string' => 'Jenis tamu lainnya harus berupa teks.',
            ]);

    
            
            $guestType = !$request->guest_type ? $request->custom_guest_type : $request->guest_type;
            Log::info('Guest type resolved', ['guest_type' => $guestType]);

            if (!$guestType) {
                return redirect()->back()->with('error', 'Jenis tamu lainnya harus diisi');
            }

            Guest::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'guest_type' => $guestType,
                'slug' => Str::slug($request->name),
                'will_attend' => 0,
                'number_of_guests' => 0,
            ]);

            return redirect()->route('home')->with('success', 'Tamu berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Menangkap semua exception dan mengirim pesan error ke session
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    

    // Menampilkan detail tamu berdasarkan slug
    public function show($slug = null)
    {
        $slug = $slug ?? 'tamu-undangan';
        $undangan = Undangan::all();
        $guest = Guest::where('slug', $slug)->first();

        if (!$guest) {
            $guest = (object) [
                'name' => 'Tamu Undangan',
                'slug' => 'tamu-undangan',
            ];
        }

        $allGreetings = Guest::whereNotNull('greeting_message')->get();

        return view('guests.show', compact('guest', 'allGreetings', 'undangan'));
    }

    // Form untuk mengedit tamu berdasarkan slug
    public function edit($slug)
    {
        // Ambil guest berdasarkan slug
        $guest = Guest::where('slug', $slug)->firstOrFail();
        
        // Tampilkan form edit tamu
        return view('guests.edit', compact('guest'));
    }

    // Memperbarui data tamu
    public function update(Request $request, $slug)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'guest_type' => 'nullable|string',
            'custom_guest_type' => 'nullable|string',
        ], [
            'name.required' => 'Nama tamu wajib diisi.',
            'name.string' => 'Nama tamu harus berupa teks.',
            'name.max' => 'Nama tamu tidak boleh lebih dari 255 karakter.',
            
            'phone_number.required' => 'Nomor WA wajib diisi.',
            'phone_number.string' => 'Nomor WA harus berupa teks.',
            'phone_number.max' => 'Nomor WA tidak boleh lebih dari 15 karakter.',
            
            
            'custom_guest_type.string' => 'Jenis tamu lainnya harus berupa teks.',
        ]);

        // Cari tamu berdasarkan slug
        $guest = Guest::where('slug', $slug)->firstOrFail();
        $guestType = $request->guest_type === null || $request->guest_type === '' ? $request->custom_guest_type : $request->guest_type;
        // Cek jika tidak ada jenis tamu yang dipilih
        if (!$guestType) {
            return redirect()->back()->with('error', 'Jenis tamu lainnya harus diisi');
        }
    
        // Perbarui data tamu
        $guest->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'guest_type' => $guestType,
            'slug' => Str::slug($request->name),
        ]);

        return redirect()->route('home')->with('success', 'Tamu berhasil diperbarui.');
    }

    // Menghapus tamu berdasarkan ID
    public function destroy($id)
    {
        $guest = Guest::find($id);
        
        if ($guest) {
            $guest->delete();
            return redirect()->route('guests.index')->with('success', 'Tamu berhasil dihapus.');
        }

        return redirect()->route('guests.index')->with('error', 'Tamu tidak ditemukan.');
    }

    public function destroyBySlug($slug)
    {
        $guest = Guest::where('slug', $slug)->firstOrFail();
        
        if ($guest) {
            $guest->delete();
            return redirect()->route('guests.index')->with('success', 'Tamu berhasil dihapus.');
        }

        return redirect()->route('guests.index')->with('error', 'Tamu tidak ditemukan.');
    }


    // Memperbarui ucapan tamu berdasarkan slug
    public function updateGreeting(Request $request, $slug)
    {
        $request->validate([
            'greeting_message' => 'required|string',
        ]);

        $guest = Guest::where('slug', $slug)->firstOrFail();

        $guest->update([
            'greeting_message' => $request->greeting_message,
        ]);

        return redirect()->route('guests.show', $slug)->with('success', 'Ucapan Anda berhasil dikirim.');
    }

    // Memperbarui RSVP tamu berdasarkan slug
    public function updateRSVP(Request $request, $slug)
    {
        $guest = Guest::where('slug', $slug)->firstOrFail();

        $request->validate([
            'will_attend' => 'required|boolean',
            'number_of_guests' => 'required|integer|min:1|max:5',
        ]);

        $guest->update([
            'will_attend' => $request->will_attend,
            'number_of_guests' => $request->number_of_guests,
        ]);

        return redirect()->route('guests.show', $slug)->with('success', 'RSVP berhasil diperbarui.');
    }

    public function exportPDF()
    {
        $guests = Guest::all();
        $pdf = Pdf::loadView('exports.guests-pdf', compact('guests'));
        return $pdf->download('daftar-tamu.pdf');
    }

    // Ekspor Excel
    public function exportExcel()
    {
        $guests = Guest::select('name', 'attended', 'guest_type')->get();

        return Excel::download(new class($guests) implements FromCollection {
            private $guests;

            public function __construct($guests)
            {
                $this->guests = $guests;
            }

            public function collection()
            {
                return $this->guests;
            }
        }, 'daftar-tamu.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'guest_file' => 'required|mimes:xls,xlsx,csv',
        ]);

        try {
            Excel::import(new GuestsImport, $request->file('guest_file'));

            return redirect()->route('guests.index')->with('success', 'Tamu berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengimpor tamu: ' . $e->getMessage());
        }
    }
    
    public function checkin()
    {
        $guests = Guest::where('attended', true)->paginate(10);
        $totalCheckedIn = $guests->count();

        return view('guests.checkin', compact('guests', 'totalCheckedIn'));
    }
    public function welcome()
    {
        // Ambil nama tamu terakhir dari cache
        $lastGuest = Cache::get('last_scanned_guest');
        $settings_events = Setting::all();
        return view('guests.welcome', ['lastGuest' => $lastGuest], compact('settings_events'));
    }

    public function printQr($slug)
    {
        $guest = Guest::where('slug', $slug)->firstOrFail();
    
        return view('guests.qr-pdf', compact('guest'));
    }
    
     public function updateRSVPJson(Request $request, $slug)
    {
        try {
            // Cari tamu berdasarkan slug
            $guest = Guest::where('slug', $slug)->firstOrFail();
            // Validasi input
            $validatedData = $request->validate([
                'will_attend' => 'required|boolean',
                'number_of_guests' => 'required|integer|min:1|max:5',
            ]);
            // Update RSVP
            $guest->update($validatedData);
            // Kirim response JSON
            return response()->json([
                'success' => true,
                'message' => 'RSVP berhasil diperbarui.',
                'guest' => [
                    'name' => $guest->name,
                    'will_attend' => $guest->will_attend,
                    'number_of_guests' => $guest->number_of_guests
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui RSVP.'
            ], 500);
        }
    }
}