@extends('layouts.app')

@section('title', 'Edit Tamu')

@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-primary-dark mb-6">Edit Tamu</h2>
        
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
        <form action="{{ route('guests.update', $guest->slug) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
            @csrf
            @method('PUT')
            
            <!-- Input Nama -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-primary-dark">Nama Tamu</label>
                <input type="text" name="name" id="name" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" value="{{ $guest->name }}" required>
            </div>

            <!-- Input Nomor WA -->
            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-primary-dark">Nomor WA</label>
                <input type="text" name="phone_number" id="phone_number" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" value="{{ $guest->phone_number }}" required>
            </div>

            <!-- Input Jenis Tamu -->
            <div class="mb-4">
                <label for="guest_type" class="block text-sm font-medium text-primary-dark">Jenis Tamu</label>
                <select name="guest_type" id="guest_type" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark">
                    <option value="">Jenis tamu lainnya</option>
                    <option value="VIP" {{ $guest->guest_type === 'VIP' ? 'selected' : '' }}>VIP</option>
                    <option value="Regular" {{ $guest->guest_type === 'Regular' ? 'selected' : '' }}>Regular</option>
                    <option value="Special" {{ $guest->guest_type === 'Special' ? 'selected' : '' }}>Special</option>
                </select>
            </div>

            <!-- Input Jenis Tamu Lainnya (Hidden jika guest_type bukan kosong) -->
            <div id="custom-guest-type-container" class="mb-4" style="display: none;">
                <label for="custom_guest_type" class="block text-sm font-medium text-primary-dark">Jenis Tamu Lainnya</label>
                <input type="text" name="custom_guest_type" id="custom_guest_type" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" value="{{ $guest->guest_type !== 'VIP' && $guest->guest_type !== 'Regular' && $guest->guest_type !== 'Special' ? $guest->guest_type : '' }}">
            </div>

            <!-- Tombol Perbarui -->
            <button type="submit" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                Perbarui
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const guestTypeSelect = document.getElementById('guest_type');
        const customGuestTypeContainer = document.getElementById('custom-guest-type-container');
        
        // Cek apakah guest_type sudah memiliki nilai
        const guestType = guestTypeSelect.value;

        // Jika guest_type kosong atau "Jenis Tamu Lainnya", tampilkan input custom
        if (guestType === "") {
            customGuestTypeContainer.style.display = 'block';
        } else {
            customGuestTypeContainer.style.display = 'none';
        }

        // Tambahkan event listener untuk perubahan pada guest_type
        guestTypeSelect.addEventListener('change', function() {
            const guestType = this.value;

            // Jika memilih "Jenis Tamu Lainnya" (kosong), tampilkan input custom
            if (guestType === "") {
                customGuestTypeContainer.style.display = 'block';
            } else {
                customGuestTypeContainer.style.display = 'none';
            }
        });
    });
</script>
@endsection
