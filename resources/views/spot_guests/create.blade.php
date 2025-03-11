@extends('layouts.app')

@section('title', 'Tambah Tamu On-The-Spot')

@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-primary-dark mb-6">Tambah Tamu On-The-Spot</h2>

        @if (session('success'))
            <div class="alert alert-success bg-green-100 text-green-800 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <div class="flex flex-col items-center justify-center">
                <div class="w-full max-w-lg text-center">
                    <h3 class="text-xl font-semibold text-primary-dark mb-4">Tata Cara Pendaftaran Tamu On-The-Spot</h3>
                    
                    <ol class="list-decimal text-left pl-6 space-y-2 mb-6">
                        <li>Pastikan tamu belum terdaftar di sistem</li>
                        <li>Klik tombol "Ambil Selfie & Daftar" di bawah</li>
                        <li>Ambil foto selfie tamu</li>
                        <li>Isi formulir data diri tamu</li>
                        <li>Klik tombol "Simpan" untuk menyelesaikan pendaftaran</li>
                    </ol>
                    
                    <div class="flex justify-center mt-4">
                        <a href="{{ route('spot-guests.take-selfie') }}" class="px-6 py-3 bg-primary-dark text-white rounded-full shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                Ambil Selfie & Daftar
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection