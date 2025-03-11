<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guest;
use App\Models\Undangan;
use App\Models\Setting;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the dashboard after login.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('search');
            $guests = Guest::select('name', 'will_attend', 'number_of_guests')
                ->when($query, function ($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'LIKE', '%' . $query . '%');
                })
                ->get();
    
            return response()->json(['guests' => $guests]);
        }
        $settings_events = Setting::all();
        $totalGuests = Guest::count(); // Jumlah undangan
        $undangan = Undangan::all();
        $totalAttended = Guest::where('attended', true)->count(); // Jumlah tamu yang hadir
        $totalNotAttended = $totalGuests - $totalAttended; // Jumlah tamu yang tidak hadir
        $totalNumberOfGuests = Guest::where('attended', true)->sum('number_of_guests'); // Total tamu yang hadir

        $guests = Guest::all();


        return view('dashboard', compact('totalGuests', 'totalAttended', 'totalNotAttended', 'totalNumberOfGuests', 'guests', 'undangan', 'settings_events'));
    }

    public function rsvp(Request $request)
    {
        if ($request->ajax()) {
            $query = $request->get('search');
            $guests = Guest::select('name', 'will_attend', 'number_of_guests')
                ->when($query, function ($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'LIKE', '%' . $query . '%');
                })
                ->get();
    
            return response()->json(['guests' => $guests]);
        }
        $settings_events = Setting::all();
        $totalGuests = Guest::count(); // Jumlah undangan
        $undangan = Undangan::all();
        $totalAttended = Guest::where('attended', true)->count(); // Jumlah tamu yang hadir
        $totalNotAttended = $totalGuests - $totalAttended; // Jumlah tamu yang tidak hadir
        $totalNumberOfGuests = Guest::where('attended', true)->sum('number_of_guests'); // Total tamu yang hadir

        $guests = Guest::all();


        return view('rsvp', compact('totalGuests', 'totalAttended', 'totalNotAttended', 'totalNumberOfGuests', 'guests', 'undangan', 'settings_events'));
    }

    public function getGuests(Request $request)
    {
        $query = $request->get('search');
        $guests = Guest::select('name', 'will_attend', 'number_of_guests')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'LIKE', '%' . $query . '%');
            })
            ->get();

        return response()->json(['guests' => $guests]);
    }


}
