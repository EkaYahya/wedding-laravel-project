@extends('layouts.app')

@section('title', 'Detail Tamu On-The-Spot')

@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-primary-dark">Detail Tamu On-The-Spot</h2>
            <a href="{{ route('spot-guests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600 focus:ring-2 focus:ring-gray-300">
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Informasi Tamu -->
                <div>
                    <h3 class="text-xl font-semibold text-primary-dark mb-4">Informasi Tamu</h3>
                    
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">Nama Tamu</span>
                        <span class="block text-lg font-semibold text-primary-dark">{{ $spotGuest->name }}</span>
                    </div>
                    
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">Nomor WA</span>
                        <span class="block text-lg font-semibold text-primary-dark">{{ $spotGuest->phone_number }}</span>
                    </div>
                    
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">Jenis Tamu</span>
                        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $spotGuest->guest_type }}
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <span class="block text-sm font-medium text-gray-500">Waktu Registrasi</span>
                        <span class="block text-lg font-semibold text-primary-dark">{{ $spotGuest->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex space-x-4 mt-6">
                        <a href="{{ route('spot-guests.edit', $spotGuest->slug) }}" class="px-4 py-2 bg-yellow-500 text-white rounded shadow hover:bg-yellow-600 focus:ring-2 focus:ring-yellow-300">
                            Edit
                        </a>
                        <form action="{{ route('spot-guests.destroy', $spotGuest->slug) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tamu ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded shadow hover:bg-red-700 focus:ring-2 focus:ring-red-300">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Foto Tamu -->
                <div class="flex flex-col items-center justify-center">
                    <h3 class="text-xl font-semibold text-primary-dark mb-4">Foto Tamu</h3>
                    
                    @if ($spotGuest->photo)
                        <img src="{{ asset('storage/' . $spotGuest->photo) }}" alt="{{ $spotGuest->name }}" class="w-full max-w-sm rounded-lg shadow-lg object-cover mb-4">
                        
                        <a href="{{ route('spot-guests.take-selfie', $spotGuest->slug) }}" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light mt-4">
                            Ambil Ulang Foto
                        </a>
                    @else
                        <div class="w-full max-w-sm h-64 flex items-center justify-center bg-gray-100 rounded-lg mb-4">
                            <span class="text-gray-500">Tidak ada foto</span>
                        </div>
                        
                        <a href="{{ route('spot-guests.take-selfie', $spotGuest->slug) }}" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light mt-4">
                            Ambil Foto
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection