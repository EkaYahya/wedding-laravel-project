
@extends('layouts.app')

@section('title', 'Check-In Tamu')

@section('content')
<div class="bg-primary-light min-h-screen py-8 ">
    <!-- Header dan Statistik -->
    <div class="flex items-center justify-between px-8 py-4 mt-20 mx-20  bg-primary-dark text-white rounded-lg shadow mb-6">
        <div class="text-center">
        <h2 class="text-4xl font-bold">{{ $totalCheckedIn }}</h2>
        <p>Tamu Sudah Check-In</p>
        </div>
    </div>

    <!-- Daftar Check-In -->
    <div class="container mx-auto px-6 pb-20">
        <h2 class="text-xl font-bold text-primary-dark mb-4">Daftar Check-In</h2>
        <div class="bg-white shadow rounded-lg p-4">
             @if($guests->isEmpty())
                <p class="text-center text-gray-500">Tidak ada tamu yang sudah check-in.</p>
            @else
                @foreach ($guests as $guest)
                    <div class="flex items-center justify-between border-b pb-4 mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-primary-dark">{{ $guest->name }}</h3>
                        </div>
                        <span class="text-xs text-gray-500"> 
                            @if($guest->attended)
                                <span class="bg-green-100 text-green-500">Sudah Ambil Voucher</span>
                            @else
                                <span class="bg-red-100 text-red-500">Belum Ambil Voucher</span>
                            @endif
                        </span>
                        
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