@extends('layouts.app')

@section('title', 'Penukaran Souvenir')

@section('content')
<div class="bg-primary-light min-h-screen py-8">
    <!-- Header dan Statistik -->
    <div class="flex items-center justify-between px-8 py-4 mt-20 mx-20  bg-primary-dark text-white rounded-lg shadow mb-6">
        <div class="text-center">
            <h2 class="text-4xl font-bold">{{ $totalSouvenirs }}</h2>
            <p>Souvenir Keluar</p>
        </div>
    </div>

    <!-- Penukaran Souvenir -->
    <div class="container mx-auto px-6 pb-20">
        <h2 class="text-xl font-bold text-primary-dark mb-4">Penukaran Souvenir</h2>
        <div class="flex gap-4 mb-6">
            <a href="{{ route('guests.exportPDF') }}" class="flex items-center px-4 py-2 bg-yellow-500 text-white rounded shadow hover:bg-yellow-600">
                <i class="fa-solid fa-file-export mr-2"></i>EXPORT
            </a>
            <a href="{{ route('souvenir.scan-qr') }}" class="flex items-center px-4 py-2 bg-primary text-white rounded shadow hover:bg-primary-dark">
                <i class="fa-solid fa-qrcode mr-2"></i>Scan QR
            </a>
            <!-- <a href="{{ route('guests.exportPDF') }}" class="flex items-center px-4 py-2 bg-primary text-white rounded shadow hover:bg-primary-dark">
                <i class="fa-solid fa-qrcode mr-2"></i>Scan Ext
            </a> -->
            <!-- <a href="{{ route('guests.exportPDF') }}" class="flex items-center px-4 py-2 bg-primary text-white rounded shadow hover:bg-primary-dark">
                <i class="fa-solid fa-search mr-2"></i>Cari Tamu
            </a> -->
        </div>

        <!-- Daftar Souvenir -->
        <div class="bg-white shadow rounded-lg p-4">
             @if($guests->isEmpty())
                <p class="text-center text-gray-500">Tidak ada tamu yang menerima souvenir.</p>
            @else
                @foreach ($guests as $guest)
                    <div class="flex items-center justify-between border-b pb-4 mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-primary-dark">{{ $guest->name }}</h3>
                        </div>
                        <span class="px-4 py-1 text-sm font-semibold text-green-700 bg-green-100 rounded-full">Terima Souvenir</span>
                    </div>
                @endforeach

                <!-- Pagination Links -->
                <div class="mt-4 flex justify-center">
                    <div class="pagination flex items-center space-x-2">
                        <!-- Previous Page Link -->
                        @if ($guests->onFirstPage())
                            <span class="px-3 py-1 bg-gray-300 text-gray-500 cursor-not-allowed rounded-md">Prev</span>
                        @else
                            <a href="{{ $guests->previousPageUrl() }}" class="px-3 py-1 bg-primary text-white rounded-md hover:bg-primary-dark">Prev</a>
                        @endif

                        <!-- Pagination Numbers -->
                        @foreach ($guests->getUrlRange(1, $guests->lastPage()) as $page => $url)
                            @if ($page == $guests->currentPage())
                                <span class="px-3 py-1 bg-primary text-white rounded-md">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 bg-gray-200 text-primary-dark rounded-md hover:bg-primary-light">{{ $page }}</a>
                            @endif
                        @endforeach

                        <!-- Next Page Link -->
                        @if ($guests->hasMorePages())
                            <a href="{{ $guests->nextPageUrl() }}" class="px-3 py-1 bg-primary text-white rounded-md hover:bg-primary-dark">Next</a>
                        @else
                            <span class="px-3 py-1 bg-gray-300 text-gray-500 cursor-not-allowed rounded-md">Next</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
