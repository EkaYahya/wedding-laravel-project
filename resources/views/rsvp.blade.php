@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


<div class="bg-primary-light min-h-screen p-6">
<div class="relative w-full mx-auto py-20">
    <!-- Daftar Tamu -->
    <h2 class="text-2xl font-bold text-primary-dark my-4">Daftar Tamu</h2>
    <div class="overflow-x-auto bg-primary-dark rounded-lg shadow mb-40">
        <div class="m-4 text-white text-lg font-semibold">
            Undangan: {{ $totalGuests }} | Hadir: {{ $totalAttended }} ({{ $totalNumberOfGuests }} Orang)
        </div>
        <div class="m-2">
            <form id="search-form" action="{{ route('guests.index') }}" method="GET" onsubmit="handleSearch(event)">
                <input type="text" id="search-input" name="search" placeholder="Cari tamu..."
                     class="px-4 py-2 border rounded-lg w-full mb-4 focus:ring-2 focus:ring-primary outline-none">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                    Cari
                </button>
            </form>
        </div>
        <table id="guests-table" class="min-w-full bg-white border border-gray-200 pb-20">
            <thead>
                <tr class="bg-primary-light border-b text-primary-dark">
                    <th class="py-2 px-4 text-left font-medium">Nama</th>
                    <th class="py-2 px-4 text-left font-medium text-center">Kehadiran</th>
                    <th class="py-2 px-4 text-left font-medium text-center">Jumlah Tamu</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($guests as $guest)
                    <tr class="border-b hover:bg-primary-light/50 transition-colors duration-200">
                        <td class="py-2 px-4 text-primary-dark">{{ $guest->name }}</td>
                        <td class="py-2 px-4 flex justify-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $guest->will_attend ? 'bg-primary-light text-primary' : 'bg-gray-100 text-gray-600' }}">
                                {{ $guest->will_attend ? 'Akan Hadir' : 'Tidak Hadir' }}
                            </span> 
                        </td>
                        <td class="py-2 px-4 text-primary-dark text-center">{{ $guest->number_of_guests ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-2 px-4 text-center text-gray-500">Belum ada tamu yang terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Pencarian Tamu -->
    <div id="searchModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <!-- Header -->
            <div class="bg-primary text-white text-center p-4 rounded-t-lg">
                <h3 class="text-xl font-bold">Cari Tamu Terdaftar</h3>
            </div>
            <!-- Body -->
            <div class="p-6">
                <form id="search-form" action="{{ route('guests.index') }}" method="GET" onsubmit="handleSearch(event)">
                    <input type="text" id="search-input" name="search" placeholder="Cari tamu..."
                        class="px-4 py-2 border rounded-lg w-full mb-4 focus:ring-2 focus:ring-primary outline-none">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark">
                        Cari
                    </button>
                </form>
                <!-- Table for displaying search results -->
                <div class="m-2 mt-4">
                    <table id="search-results" class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-primary-light border-b text-primary-dark">
                                <th class="py-2 px-4 text-left font-medium">Nama</th>
                                <th class="py-2 px-4 text-left font-medium">No. Telepon</th>
                            </tr>
                        </thead>
                        <tbody id="search-results-body">
                            <!-- Search results will be dynamically inserted here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- Footer -->
            <button id="closeSearchModal" class="w-full flex items-center justify-center bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">
                <i class="fa-solid fa-times mr-2"></i> Tutup
            </button>
        </div>
    </div>
</div>

@endsection
