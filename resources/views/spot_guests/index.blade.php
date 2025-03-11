@extends('layouts.app')

@section('title', 'Daftar Tamu On-The-Spot')

@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <div class="container mx-auto px-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-primary-dark">Daftar Tamu On-The-Spot</h2>
            <a href="{{ route('spot-guests.create') }}" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                Tambah Tamu On-The-Spot
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

        <!-- Statistik Tamu -->
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-primary-dark mb-2">Total Tamu On-The-Spot</h3>
                <p class="text-3xl font-bold text-primary">{{ $totalSpotGuests }}</p>
            </div>
        </div>

        <!-- Form Pencarian -->
        <div class="bg-white p-4 rounded-lg shadow mb-6">
            <form action="{{ route('spot-guests.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, nomor WA, atau jenis tamu..." class="w-full p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark">
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                        Cari
                    </button>
                    <a href="{{ route('spot-guests.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded shadow hover:bg-gray-600 focus:ring-2 focus:ring-gray-300">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Tombol Export -->
        <div class="flex gap-4 mb-6">
            <a href="{{ route('spot-guests.export-pdf') }}" class="px-4 py-2 bg-red-600 text-white rounded shadow hover:bg-red-700 focus:ring-2 focus:ring-red-300">
                Export PDF
            </a>
        </div>

        <!-- Tabel Daftar Tamu -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nomor WA
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis Tamu
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Foto
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($spotGuests as $guest)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $guest->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-500">{{ $guest->phone_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $guest->guest_type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($guest->photo)
                                        <a href="{{ asset('storage/' . $guest->photo) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $guest->photo) }}" alt="{{ $guest->name }}" class="h-10 w-10 rounded-full object-cover">
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-500">No Photo</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('spot-guests.show', $guest->slug) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="{{ route('spot-guests.edit', $guest->slug) }}" class="text-yellow-600 hover:text-yellow-900">Edit</a>
                                        <form action="{{ route('spot-guests.destroy', $guest->slug) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tamu ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                    Tidak ada data tamu on-the-spot.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-6 py-4 border-t">
                {{ $spotGuests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection