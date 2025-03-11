@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')


<div class="bg-primary-light min-h-screen p-6">
<div class="relative w-full mx-auto py-20">
    <!-- Background Image -->
    <div class="relative">
    @foreach ($undangan as $undangan)
        @foreach($settings_events as $event)
        <img src="{{ $event->image_url ?? 'https://via.placeholder.com/250x150' }}" alt="Wedding Image" class="w-full h-auto rounded-lg shadow-md">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-gray-900 opacity-50 rounded-lg"></div>
        
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 opacity-90 w-10/12 max-w-sm bg-blue-900 text-white rounded-lg shadow-xl">
            <div class="p-4">
            <div class="flex flex-col items-center text-center space-y-2">
                
                <div class="text-sm text-gray-300 uppercase tracking-wider">
                {{$undangan->nama_title}}
                </div>
                
                <div class="text-xl font-semibold">
                {{$undangan->nama_pasangan}}
                </div>
                
                <div class="text-sm text-gray-300">
                {{ $undangan->tempat_resepsi }} | {{ \Carbon\Carbon::parse($undangan->tanggal_resepsi)->format('d-m-Y') }}
                </div>
            </div>
            </div>
        </div>
        </div>
        @endforeach
        @endforeach
    </div>
    <!-- Menu Cards -->
    <div class="grid grid-cols-2 gap-4">
        <!-- Card 1: Data Tamu -->
        <div class="bg-primary-dark text-white p-4 rounded-lg shadow-lg flex justify-center items-center transition-shadow duration-300">
            <a href="{{ route('showTamu') }}" 
               class="flex flex-col items-center text-center {{ request()->routeIs('home') ? 'text-white' : 'text-gray-400' }} 
                    hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                <span class="text-3xl mb-2 text-white hover:text-yellow-300">
                    <i class="fa-solid fa-users"></i>
                </span>
                <span class="text-sm font-bold text-white">Data Tamu</span>
            </a>
        </div>

        <div class="bg-primary-dark text-white p-4 rounded-lg shadow-lg flex justify-center items-center transition-shadow duration-300">
            <a href="{{ route('home') }}" 
               class="flex flex-col items-center text-center {{ request()->routeIs('home') ? 'text-white' : 'text-gray-400' }} 
                    hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                <span class="text-3xl mb-2 text-white hover:text-yellow-300">
                    <i class="fa-solid fa-book"></i>
                </span>
                <span class="text-sm font-bold text-white">Kehadiran</span>
            </a>
        </div>

        <!-- Card: Trigger Modal for Check-in -->
        <div class="bg-primary-dark text-white p-4 rounded-lg shadow-lg flex justify-center items-center transition-shadow duration-300">
            <button id="triggerModal" class="flex flex-col items-center text-center hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                <span class="text-3xl mb-2 text-white hover:text-yellow-300">
                    <i class="fa-solid fa-qrcode"></i>
                </span>
                <span class="text-sm font-bold text-white">Check-in</span>
            </button>
        </div>

        <div class="bg-primary-dark text-white p-4 rounded-lg shadow-lg flex justify-center items-center transition-shadow duration-300">
            <a href="{{ route('souvenir.index') }}" 
               class="flex flex-col items-center text-center {{ request()->routeIs('souvenir.index') ? 'text-white' : 'text-gray-400' }} 
                    hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                <span class="text-3xl mb-2 text-white hover:text-yellow-300">
                    <i class="fa-solid fa-hand-holding-heart"></i>
                </span>
                <span class="text-sm font-bold text-white">Souvenirs</span>
            </a>
        </div>

        <div class="bg-primary-dark text-white p-4 rounded-lg shadow-lg flex justify-center items-center transition-shadow duration-300">
            <a href="{{ route('guests.show', ['slug' => 'rsvp']) }}" 
               class="flex flex-col items-center text-center {{ request()->routeIs('guests.show') ? 'text-white' : 'text-gray-400' }} 
                hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                <span class="text-3xl mb-2 text-white hover:text-yellow-300">
                    <i class="fa-solid fa-comments"></i>
                </span>
                <span class="text-sm font-bold text-white">RSVP</span>
            </a>
        </div>

        <div class="bg-primary-dark text-white p-4 rounded-lg shadow-lg flex justify-center items-center transition-shadow duration-300">
            <a href="{{ route('guests.welcome') }}" target="_blank"
               class="flex flex-col items-center text-center {{ request()->routeIs('welcome') ? 'text-white' : 'text-gray-400' }} 
                    hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                <span class="text-3xl mb-2 text-white hover:text-yellow-300">
                    <i class="fa-solid fa-desktop"></i>
                </span>
                <span class="text-sm font-bold text-white">Welcome</span>
            </a>
        </div>
    </div>

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
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $guest->attended ? 'bg-primary-light text-primary' : 'bg-gray-100 text-gray-600' }}">
                                {{ $guest->attended ? 'Hadir' : 'Tidak' }}
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

    <!-- Modal Pop-up -->
    <div id="checkInModal" class="fixed inset-0 flex items-center justify-center mx-5 hidden z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <!-- Header -->
            <div class="bg-primary-dark text-white text-center p-4 rounded-t-lg">
                <h3 class="text-xl font-bold">Check-in Tamu</h3>
            </div>
            <!-- Body -->
            <div class="p-6 text-center">
                <p class="text-gray-700 mb-6">Pilih salah satu cara check-in:</p>
                <!-- Buttons -->
                <div class="space-y-4">
                    <a href="{{ route('scan-qr.show') }}" 
                        class="flex items-center justify-center text-center bg-primary-dark  text-white  px-4 py-2 rounded hover:bg-primary transition
                            hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                            <i class="fa-solid fa-qrcode mr-2"></i> Scan QR-Code
                    </a>
                    <button id="triggerSearchModal" class="w-full items-center justify-center text-center bg-primary-dark text-white  px-4 py-2 rounded hover:bg-primary transition
                        hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                        <i class="fa-solid fa-search mr-2"></i> Cari Tamu Terdaftar
                    </button>
                    <a href="{{ route('guests.create') }}" class="flex items-center justify-center text-center bg-primary-dark  text-white  px-4 py-2 rounded hover:bg-primary transition
                        hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                        <i class="fa-solid fa-user-plus mr-2"></i> Input Tamu Baru
                    </a>
                    <a href="{{ route('spot-guests.create') }}" class="flex items-center justify-center text-center bg-primary-dark  text-white  px-4 py-2 rounded hover:bg-primary transition
                        hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                        <i class="fa-solid fa-user-plus mr-2"></i> Input Tamu Baru On The Spot
                    </a>
                    <a href="{{ route('guests.checkin') }}" class="flex items-center justify-center text-center bg-primary-dark  text-white  px-4 py-2 rounded hover:bg-primary transition
                        hover:text-yellow-300 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 transition-transform duration-200">
                        <i class="fa-solid fa-user-plus mr-2"></i> Pengambilan Voucher Souvenir
                    </a>
                    <button id="closeModalBtn" class="w-full flex items-center justify-center bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500 transition">
                        <i class="fa-solid fa-times mr-2"></i> Tutup
                    </button>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Cari Tamu Terdaftar -->
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

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Get elements
    const checkInModal = document.getElementById('checkInModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const triggerModal = document.getElementById('triggerModal');
    const searchModal = document.getElementById('searchModal');
    const closeSearchModalBtn = document.getElementById('closeSearchModal');
    const triggerSearchModal = document.getElementById('triggerSearchModal');

    // Utility function to toggle modals
    const openModal = (modal) => {
        modal.classList.remove('hidden');
    };

    const closeModal = (modal) => {
        modal.classList.add('hidden');
    };

    // Open search modal
    triggerSearchModal.addEventListener('click', () => {
        openModal(searchModal);
        closeModal(checkInModal); // Ensure other modals are closed
    });

    // Close search modal
    closeSearchModalBtn.addEventListener('click', () => {
        closeModal(searchModal);
    });

    // Open check-in modal
    triggerModal.addEventListener('click', () => {
        openModal(checkInModal);
        closeModal(searchModal); // Ensure other modals are closed
    });

    // Close check-in modal
    closeModalBtn.addEventListener('click', () => {
        closeModal(checkInModal);
    });

    // Close modals on outside click
    const handleOutsideClick = (modal) => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal(modal);
            }
        });
    };

    handleOutsideClick(searchModal); // Close search modal when clicking outside
    handleOutsideClick(checkInModal); // Close check-in modal when clicking outside
});

</script>
