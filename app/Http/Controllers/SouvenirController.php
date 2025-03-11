<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SouvenirController extends Controller
{
    public function index()
    {
        $guests = Guest::select('name', 'souvenir_received')
            ->where('souvenir_received', true)
            ->paginate(10);

        $totalSouvenirs = $guests->total();

        return view('souvenir.index', compact('guests', 'totalSouvenirs'));
    }

    public function showQR()
    {
        return view('souvenir.scan-qr');
    }

    public function updateSouvenir($slug)
    {
        try {
            $guest = Guest::where('slug', $slug)->firstOrFail();
    
            // Periksa apakah souvenir sudah pernah diambil
            if ($guest->souvenir_received) {
                return response()->json([
                    'success' => false,
                    'message' => 'Souvenir sudah diambil sebelumnya.',
                    'souvenir_taken' => true
                ]);
            }
    
            // Tandai souvenir sebagai diambil
            $guest->souvenir_received = true;
            $guest->save();
    
            return response()->json([
                'success' => true,
                'guest' => [
                    'name' => $guest->name,
                    'souvenir_received' => $guest->souvenir_received,
                ]
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Tamu tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }


    public function checkSouvenir($slug)
    {
        try {
            $guest = Guest::where('slug', $slug)->firstOrFail();

            return response()->json([
                'success' => true,
                'souvenir_taken' => $guest->souvenir_received,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Tamu tidak ditemukan'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan.'], 500);
        }
    }
}
