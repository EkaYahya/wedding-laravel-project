@extends('layouts.app')

@section('title', 'Daftar Tamu')
<!-- route kehadiran-->
@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <!-- Statistik -->
    <div class="grid grid-cols-3 gap-6 mx-8 mb-6 mt-14">
        @foreach([
            ['title' => 'Undangan', 'value' => $totalGuests],
            ['title' => 'Hadir', 'value' => $totalAttended],
            ['title' => 'Tdk Hadir', 'value' => $totalGuests - $totalAttended],
        ] as $stat)
        <div class="p-4 bg-primary-dark text-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
            <p class="text-2xl font-bold text-center">{{ $stat['value'] }}</p>    
            <h4 class="text-sm font-semibold text-center">{{ $stat['title'] }}</h4>   
        </div>
        @endforeach
    </div>

    <div class="container mx-auto px-6 pb-20">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-primary-dark">Kehadiran</h1>
        </div>
        <!-- Tombol Ekspor PDF dan Excel -->
        <div class="flex justify-start items-center mb-4">
            <a href="{{ route('guests.exportPDF') }}" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light mr-2">
                Export PDF
            </a>
            <a href="{{ route('guests.exportExcel') }}" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                Export Excel
            </a>
            
        </div>

        <!-- Tabel Tamu -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-4 bg-primary-dark flex justify-between items-center">
                <form id="search-form" class="flex items-center gap-2" method="GET" action="{{ route('guests.index') }}">
                    <input type="text" id="search-input" name="search" placeholder="Cari tamu..." class="px-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-primary outline-none" aria-label="Cari tamu" value="{{ request('search') }}">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition focus:ring-2 focus:ring-primary-light" aria-label="Cari">Cari</button>
                    <div class="relative">
                    
                </div>
                </form>
                <button id="sortDropdownButton" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition focus:ring-2 focus:ring-primary-light text-sm" aria-label="Sort Options">
                        Urutkan
                    </button>     
            </div>
            <div id="sortDropdown" class="absolute right-0 mt-0 w-48 bg-white border rounded-lg shadow-lg hidden z-50">
                        <a href="{{ route('guests.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => 'asc'])) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Nama (A-Z)</a>
                        <a href="{{ route('guests.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => 'desc'])) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Nama (Z-A)</a>
                        <a href="{{ route('guests.index', array_merge(request()->query(), ['sort' => 'guest_type', 'direction' => 'asc'])) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Jenis Tamu (A-Z)</a>
                        <a href="{{ route('guests.index', array_merge(request()->query(), ['sort' => 'guest_type', 'direction' => 'desc'])) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Jenis Tamu (Z-A)</a>
                    </div>
            <table class="min-w-full bg-white relative">
            <thead class="bg-primary-dark text-primary-light">
                <tr>
                    <th class="py-3 px-4 border-b text-start">
                        Nama
                    </th>
                    <th class="py-3 px-2 border-b">
                        <a href="{{ route('guests.create') }}" class="px-2 py-2 bg-primary-white text-bg-primary rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light" title="Add New Guest">
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($guests as $guest)
                <tr class="hover:bg-primary-light/50 transition-colors duration-200">
                    <td class="py-3 px-4 border-b text-primary-dark">{{ $guest->name }}<br>
                    <span class="text-xs text-gray-500">
                        {{ $guest->phone_number }} | {{ $guest->guest_type }} | 
                        @if($guest->will_attend)
                            <span class="bg-white-100 text-white-500">akan hadir |</span>
                        @else
                            <span class="bg-white-100 text-white-500">tdk akan hadir |</span>
                        @endif
                        @if($guest->attended)
                            <span class="bg-green-100 text-green-500">hadir</span>
                        @else
                            <span class="bg-red-100 text-red-500">tdk hadir</span>
                        @endif
                    </span>
                    </td>
                    <td class="py-3 px-4 border-b text-center relative">
                        <div class="relative inline-block text-xs text-gray-500">{{ $guest->updated_at }}</div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
            <div class="m-4">
                {{ $guests->links() }}
            </div>
            <!-- Dropdown Container -->
            <div id="dropdown-container" class="hidden absolute right-0 bg-white border rounded-lg shadow-md mt-2 text-sm z-50 w-48">
                <ul id="dropdown-actions">
                    <!-- Actions will be dynamically inserted here -->
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('sortDropdownButton').addEventListener('click', function() {
        document.getElementById('sortDropdown').classList.toggle('hidden');
    });
</script>

@endsection