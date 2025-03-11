@extends('layouts.app')

@section('title', 'Tambah Tamu')

@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-primary-dark mb-6">Tambah Tamu</h2>

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

        <form action="{{ route('guests.import') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow mb-4">
            @csrf
            <div class="mb-4">
                <label for="guest_file" class="block text-sm font-medium text-primary-dark">Import Tamu (Excel/CSV)</label>
                <input type="file" name="guest_file" id="guest_file" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                Import
            </button>
        </form>
        <form action="{{ route('guests.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
            @csrf
            <!-- Input Nama -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-primary-dark">Nama Tamu</label>
                <input type="text" name="name" id="name" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" >
            </div>
            
           <!-- Input Nomor WA -->
           <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-primary-dark">Nomor WA</label>
                <input type="tel" name="phone_number" id="phone_number" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" >
            </div>

           <!-- Dropdown Jenis Tamu -->
           <div class="mb-4">
                <label for="guest_type" class="block text-sm font-medium text-primary-dark">Jenis Tamu</label>
                <select name="guest_type" id="guest_type" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" >
                    <option value="VIP">VIP</option>
                    <option value="Regular">Regular</option>
                    <option value="Special">Special</option>
                    <option value="">Masukkan Jenis Tamu Baru...</option>
                </select>
            </div>

            <!-- Input Custom Jenis Tamu (Jika diperlukan) -->
            <div class="mb-4" id="custom-guest-type-container" style="display: none;">
                <label for="custom_guest_type" class="block text-sm font-medium text-primary-dark">Jenis Tamu Lainnya</label>
                <input type="text" name="custom_guest_type" id="custom_guest_type" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark">
            </div>
            
            <!-- Tombol Simpan -->
            <button type="submit" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                Simpan
            </button>
        </form>
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