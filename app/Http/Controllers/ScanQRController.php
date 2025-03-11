<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class ScanQRController extends Controller
{
    public function show()
    {
        $guests = Guest::all();
        return view('scan-qr', compact('guests'));
    }

    // Di ScanQRController::updateAttendance, tambahkan ini
    public function updateAttendance($slug)
    {
        try {
            $guest = Guest::where('slug', $slug)->firstOrFail();
            
            if ($guest->attended) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tamu sudah melakukan scan sebelumnya.'
                ], 400);
            }
            
            $guest->update(['attended' => true]);
            
            // Simpan ke cache - name dan tipe tamu
            Cache::put('last_scanned_guest', $guest->name, now()->addHours(24));
            Cache::put('last_scanned_guest_type', $guest->guest_type, now()->addHours(24));
            
            return response()->json([
                'success' => true,
                'guest' => $guest->only(['name', 'will_attend', 'number_of_guests', 'guest_type'])
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tamu tidak ditemukan: ' . $e->getMessage()
            ], 404);
        }
    }
    
    public function updateGuestCount(Request $request, $slug)
    {
        try {
            $validated = $request->validate([
                'number_of_guests' => ['required', 'integer', 'min:1']
            ]);
            
            $guest = Guest::where('slug', $slug)->firstOrFail();
            $guest->update($validated);
            
            return response()->json([
                'success' => true,
                'guest' => $guest->only(['name', 'will_attend', 'number_of_guests', 'guest_type'])
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tamu tidak ditemukan atau input tidak valid'
            ], 404);
        }
    }
}