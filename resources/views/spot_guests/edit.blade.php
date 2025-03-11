@extends('layouts.app')

@section('title', 'Edit Tamu On-The-Spot')

@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-primary-dark">Edit Tamu On-The-Spot</h2>
            <a href="{{ route('spot-guests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600 focus:ring-2 focus:ring-gray-300">
                Kembali
            </a>
        </div>

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

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Form -->
            <div class="bg-white p-6 rounded-lg shadow">
                <form action="{{ route('spot-guests.update', $spotGuest->slug) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Input Nama -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-primary-dark">Nama Tamu</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $spotGuest->name) }}" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" required>
                        @error('name')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Input Nomor WA -->
                    <div class="mb-4">
                        <label for="phone_number" class="block text-sm font-medium text-primary-dark">Nomor WA</label>
                        <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number', $spotGuest->phone_number) }}" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" required>
                        @error('phone_number')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dropdown Jenis Tamu -->
                    <div class="mb-4">
                        <label for="guest_type" class="block text-sm font-medium text-primary-dark">Jenis Tamu</label>
                        <select name="guest_type" id="guest_type" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark">
                            <option value="VIP" {{ old('guest_type', $spotGuest->guest_type) == 'VIP' ? 'selected' : '' }}>VIP</option>
                            <option value="Regular" {{ old('guest_type', $spotGuest->guest_type) == 'Regular' ? 'selected' : '' }}>Regular</option>
                            <option value="Special" {{ old('guest_type', $spotGuest->guest_type) == 'Special' ? 'selected' : '' }}>Special</option>
                            <option value="" {{ !in_array(old('guest_type', $spotGuest->guest_type), ['VIP', 'Regular', 'Special']) ? 'selected' : '' }}>Masukkan Jenis Tamu Baru...</option>
                        </select>
                        @error('guest_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Input Custom Jenis Tamu (Jika diperlukan) -->
                    <div class="mb-4" id="custom-guest-type-container" style="{{ !in_array(old('guest_type', $spotGuest->guest_type), ['VIP', 'Regular', 'Special']) ? 'display: block;' : 'display: none;' }}">
                        <label for="custom_guest_type" class="block text-sm font-medium text-primary-dark">Jenis Tamu Lainnya</label>
                        <input type="text" name="custom_guest_type" id="custom_guest_type" value="{{ !in_array(old('guest_type', $spotGuest->guest_type), ['VIP', 'Regular', 'Special']) ? old('custom_guest_type', $spotGuest->guest_type) : '' }}" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark">
                        @error('custom_guest_type')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Tombol Update -->
                    <div class="flex justify-between mt-6">
                        <button type="submit" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                            Perbarui Data
                        </button>
                        
                        <a href="{{ route('spot-guests.take-selfie', $spotGuest->slug) }}" class="px-4 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 focus:ring-2 focus:ring-blue-300">
                            Ubah Foto
                        </a>
                    </div>
                </form>
            </div>
            
            <!-- Preview Foto -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-primary-dark mb-4">Foto Tamu</h3>
                
                <div class="flex flex-col items-center justify-center">
                    @if ($spotGuest->photo)
                        <img src="{{ asset('storage/' . $spotGuest->photo) }}" alt="{{ $spotGuest->name }}" class="w-full max-w-sm rounded-lg shadow-lg object-cover mb-4">
                    @else
                        <div class="w-full max-w-sm h-64 flex items-center justify-center bg-gray-100 rounded-lg mb-4">
                            <span class="text-gray-500">Tidak ada foto</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Menambahkan event listener pada dropdown
    document.getElementById('guest_type').addEventListener('change', function() {
        const customGuestTypeContainer = document.getElementById('custom-guest-type-container');
        const guestType = this.value;
        
        // Jika memilih "Jenis Tamu Lainnya", tampilkan input custom
        if (guestType === "") {
            customGuestTypeContainer.style.display = 'block';
        } else {
            customGuestTypeContainer.style.display = 'none';
        }
    });
</script>
@endsection