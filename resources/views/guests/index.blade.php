@extends('layouts.app')

@section('title', 'Daftar Tamu')
<!-- route kehadiran-->
@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <!-- Statistik -->
    <div class="grid grid-cols-3 gap-6 mx-8 mb-6 mt-14">
        @foreach([
            ['title' => 'Undangan', 'value' => $totalGuests],
            ['title' => 'Hadir', 'value' => $totalAttended],
            ['title' => 'Tdk Hadir', 'value' => $totalGuests - $totalAttended],
        ] as $stat)
        <div class="p-4 bg-primary-dark text-white rounded-lg shadow hover:shadow-lg transition-shadow duration-300">
            <p class="text-2xl font-bold text-center">{{ $stat['value'] }}</p>    
            <h4 class="text-sm font-semibold text-center">{{ $stat['title'] }}</h4>   
        </div>
        @endforeach
    </div>

    <div class="container mx-auto px-6 pb-20">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-primary-dark">Kehadiran</h1>
        </div>
        <!-- Tombol Ekspor PDF dan Excel -->
        <div class="flex justify-start items-center mb-4">
            <a href="{{ route('guests.exportPDF') }}" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light mr-2">
                Export PDF
            </a>
            <a href="{{ route('guests.exportExcel') }}" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                Export Excel
            </a>
            
        </div>

        <!-- Tabel Tamu -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="p-4 bg-primary-dark flex justify-between items-center">
                <form id="search-form" class="flex items-center gap-2" method="GET" action="{{ route('guests.index') }}">
                    <input type="text" id="search-input" name="search" placeholder="Cari tamu..." class="px-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-primary outline-none" aria-label="Cari tamu" value="{{ request('search') }}">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition focus:ring-2 focus:ring-primary-light" aria-label="Cari">Cari</button>
                    <div class="relative">
                    
                </div>
                </form>
                <button id="sortDropdownButton" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition focus:ring-2 focus:ring-primary-light text-sm" aria-label="Sort Options">
                        Urutkan
                    </button>     
            </div>
            <div id="sortDropdown" class="absolute right-0 mt-0 w-48 bg-white border rounded-lg shadow-lg hidden z-50">
                        <a href="{{ route('guests.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => 'asc'])) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Nama (A-Z)</a>
                        <a href="{{ route('guests.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => 'desc'])) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Nama (Z-A)</a>
                        <a href="{{ route('guests.index', array_merge(request()->query(), ['sort' => 'guest_type', 'direction' => 'asc'])) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Jenis Tamu (A-Z)</a>
                        <a href="{{ route('guests.index', array_merge(request()->query(), ['sort' => 'guest_type', 'direction' => 'desc'])) }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Jenis Tamu (Z-A)</a>
                    </div>
            <table class="min-w-full bg-white relative">
            <thead class="bg-primary-dark text-primary-light">
                <tr>
                    <th class="py-3 px-4 border-b text-start">
                        Nama
                    </th>
                    <th class="py-3 px-2 border-b">
                        <a href="{{ route('guests.create') }}" class="px-2 py-2 bg-primary-white text-bg-primary rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light" title="Add New Guest">
                            <i class="fa-solid fa-user-plus"></i>
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($guests as $guest)
                <tr class="hover:bg-primary-light/50 transition-colors duration-200">
                    <td class="py-3 px-4 border-b text-primary-dark">{{ $guest->name }}<br>
                    <span class="text-xs text-gray-500">
                        {{ $guest->phone_number }} | {{ $guest->guest_type }} | 
                        @if($guest->will_attend)
                            <span class="bg-white-100 text-white-500">akan hadir |</span>
                        @else
                            <span class="bg-white-100 text-white-500">tdk akan hadir |</span>
                        @endif
                        @if($guest->attended)
                            <span class="bg-green-100 text-green-500">hadir</span>
                        @else
                            <span class="bg-red-100 text-red-500">tdk hadir</span>
                        @endif
                    </span>
                    </td>
                    <td class="py-3 px-4 border-b text-center relative">
                        <div class="relative inline-block">
                            <button onclick="toggleDropdown(this, '{{ $guest->slug }}')" class="px-3 py-1 bg-primary text-white rounded shadow hover:bg-primary-dark focus:ring-2 focus:ring-primary-light">
                                <i class="fa-solid fa-cog"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            </table>
            <div class="m-4">
                {{ $guests->links() }}
            </div>
            <!-- Dropdown Container -->
            <div id="dropdown-container" class="hidden absolute right-0 bg-white border rounded-lg shadow-md mt-2 text-sm z-50 w-48">
                <ul id="dropdown-actions">
                    <!-- Actions will be dynamically inserted here -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Jumlah Tamu -->
<div id="guestCountModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4">Jumlah Tamu</h2>
        <form id="guestCountForm" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="number_of_guests" class="block text-lg mb-2">Jumlah Orang Yang Bersama Tamu:</label>
                <select id="number_of_guests" name="number_of_guests" class="w-full p-2 border border-gray-300 rounded">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" id="close-modal-btn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle Sort Dropdown
    document.getElementById('sortDropdownButton').addEventListener('click', function() {
        document.getElementById('sortDropdown').classList.toggle('hidden');
    });

    // Toggle Dropdown Function
    function toggleDropdown(button, guestSlug) {
        const dropdown = document.getElementById('dropdown-container');
        const rect = button.getBoundingClientRect();

        // Posisi dropdown mengikuti posisi tombol
        dropdown.style.top = `${rect.bottom + window.scrollY}px`;
        dropdown.style.left = `${rect.left + window.scrollX}px`;
        dropdown.classList.toggle('hidden');

        // Susun item dropdown dengan aksi untuk guest ini
        const actions = [
            {
                icon: 'fa-solid fa-check-circle',
                label: 'Hadir',
                action: `markAttendance('${guestSlug}', true)`
            },
            {
                icon: 'fa-solid fa-users',
                label: 'Jumlah Tamu',
                action: `updateGuestCount('${guestSlug}')`
            }
        ];

        const dropdownActions = document.getElementById('dropdown-actions');
        dropdownActions.innerHTML = actions.map(action => {
            if (action.method) {
                // Untuk action yang butuh method (DELETE, PUT, dll)
                return `
                    <li>
                        <form action="${action.url}" method="POST" class="w-full">
                            @csrf
                            @method('${action.method}')
                            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-primary-light hover:text-primary-dark">
                                <i class="${action.icon} mr-2"></i> ${action.label}
                            </button>
                        </form>
                    </li>
                `;
            } else if (action.action) {
                // Untuk action yang memanggil JavaScript
                return `
                    <li>
                        <button onclick="${action.action}" class="block w-full text-left px-4 py-2 hover:bg-primary-light hover:text-primary-dark">
                            <i class="${action.icon} mr-2"></i> ${action.label}
                        </button>
                    </li>
                `;
            } else {
                // Untuk action biasa (link)
                return `
                    <li>
                        <a href="${action.url}" class="block px-4 py-2 hover:bg-primary-light hover:text-primary-dark">
                            <i class="${action.icon} mr-2"></i> ${action.label}
                        </a>
                    </li>
                `;
            }
        }).join('');
    }

    // Fungsi untuk menandai kehadiran tamu
    function markAttendance(guestSlug, attended) {
        if (!confirm(`Apakah Anda yakin ingin menandai tamu ini sebagai ${attended ? 'hadir' : 'tidak hadir'}?`)) {
            return;
        }

        // Menutup dropdown
        document.getElementById('dropdown-container').classList.add('hidden');

        // Mengirim request untuk mengubah status kehadiran
        fetch(`/guests/${guestSlug}/update-attendance`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                attended: attended
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload halaman untuk menampilkan perubahan
                window.location.reload();
            } else {
                alert('Gagal mengubah status kehadiran tamu');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat mengubah status kehadiran');
        });
    }

    // Fungsi untuk membuka modal jumlah tamu
    function updateGuestCount(guestSlug) {
        // Menutup dropdown
        document.getElementById('dropdown-container').classList.add('hidden');
        
        // Simpan guestSlug untuk form submission
        document.getElementById('guestCountForm').dataset.guestSlug = guestSlug;
        
        // Tampilkan modal
        document.getElementById('guestCountModal').classList.remove('hidden');
        
        // Fetch jumlah tamu saat ini (opsional)
        fetch(`/guests/${guestSlug}`)
            .then(response => response.json())
            .then(data => {
                if (data.number_of_guests) {
                    document.getElementById('number_of_guests').value = data.number_of_guests;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Lanjutkan dengan nilai default jika gagal fetch
            });
    }

    // Handle submit form jumlah tamu
    document.getElementById('guestCountForm').addEventListener('submit', function(event) {
        event.preventDefault();
        
        const guestSlug = this.dataset.guestSlug;
        const numberOfGuests = document.getElementById('number_of_guests').value;
        
        fetch(`/guests/${guestSlug}/update-guest-count`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                number_of_guests: numberOfGuests
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Tutup modal
                document.getElementById('guestCountModal').classList.add('hidden');
                // Refresh halaman untuk menampilkan perubahan
                window.location.reload();
            } else {
                alert('Gagal memperbarui jumlah tamu');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui jumlah tamu');
        });
    });

    // Menutup modal
    document.getElementById('close-modal-btn').addEventListener('click', function() {
        document.getElementById('guestCountModal').classList.add('hidden');
    });

    // Menutup dropdown ketika klik di luar
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdown-container');
        const sortDropdown = document.getElementById('sortDropdown');
        
        // Menutup dropdown aksi jika klik di luar
        if (dropdown && !dropdown.contains(event.target) && !event.target.closest('button[onclick^="toggleDropdown"]')) {
            dropdown.classList.add('hidden');
        }
        
        // Menutup dropdown sort jika klik di luar
        if (sortDropdown && !sortDropdown.contains(event.target) && event.target.id !== 'sortDropdownButton') {
            sortDropdown.classList.add('hidden');
        }
    });

    // Menutup modal jika klik di luar konten modal
    document.getElementById('guestCountModal').addEventListener('click', function(event) {
        if (event.target === this) {
            this.classList.add('hidden');
        }
    });
</script>

@endsection