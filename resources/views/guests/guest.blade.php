@extends('layouts.app')

@section('title', 'Daftar Tamu')
<!-- route data tamu-->
@section('content')
@foreach ($undangan as $undangan)
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <div class="max-w-sm bg-blue-900 text-white rounded-lg shadow-xl mx-auto mt-20">
        <div class="p-4">
            <div class="flex flex-col items-center text-center space-y-2">
                <h1 class="text-2xl font-bold">{{ $undangan->nama_title }}</h1>
                <p>{{ $undangan->keterangan_resepsi }}</p>
                <p>{{ $undangan->tempat_resepsi }} | {{ \Carbon\Carbon::parse($undangan->tanggal_resepsi)->format('d-m-Y') }}</p>
            </div>
        </div>
    </div>
    @endforeach

    <div class="container mx-auto px-6 pb-20 mt-5">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold text-primary-dark">Data Tamu</h2>
        </div>
        <!-- Tombol Ekspor PDF dan Excel -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex">
                <a href="{{ route('guests.exportPDF') }}"
                   class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light mr-2">
                    Export PDF
                </a>
                <a href="{{ route('guests.exportExcel') }}"
                   class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                    Export Excel
                </a>
            </div>
            
            <!-- Urutkan Button -->
            <div class="relative">
                <button id="urutkan-button" type="button" 
                    class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Urutkan
                </button>
                <div id="sort-dropdown" class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg z-50 hidden">
                    <ul class="py-1">
                        <li><a href="#" data-sort="name" data-dir="asc" class="sort-option block px-4 py-2 text-gray-700 hover:bg-gray-100">Nama (A-Z)</a></li>
                        <li><a href="#" data-sort="name" data-dir="desc" class="sort-option block px-4 py-2 text-gray-700 hover:bg-gray-100">Nama (Z-A)</a></li>
                        <li><a href="#" data-sort="guest_type" data-dir="asc" class="sort-option block px-4 py-2 text-gray-700 hover:bg-gray-100">Jenis Tamu (A-Z)</a></li>
                        <li><a href="#" data-sort="guest_type" data-dir="desc" class="sort-option block px-4 py-2 text-gray-700 hover:bg-gray-100">Jenis Tamu (Z-A)</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Tabel Tamu -->
        <div class="bg-white shadow rounded-lg overflow-hidden" style="border-bottom: 40px solid white;">
            <div class="p-4 bg-primary-dark">
                <!-- Search Control -->
                <div class="flex items-center gap-2">
                    <div class="relative flex-grow">
                        <input type="text" id="search-input" placeholder="Cari tamu..."
                           class="px-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-primary outline-none"
                           aria-label="Cari tamu">
                    </div>
                    <button id="search-btn" type="button"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition focus:ring-2 focus:ring-primary-light"
                            aria-label="Cari">
                        Cari
                    </button>
                </div>
            </div>
            
            <!-- Table Container - This part gets updated by AJAX -->
            <div id="guest-table-container">
                <table class="min-w-full bg-white relative">
                    <thead class="bg-primary-dark text-primary-light">
                        <tr>
                            <th class="py-3 px-4 border-b text-start">Nama</th>
                            <th class="py-3 px-4 border-b w-12">
                                <a href="{{ route('guests.create') }}"
                                    class="px-4 py-2 bg-primary-white text-bg-primary rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                                    <i class="fa-solid fa-user-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="guests-table-body">
                        @foreach ($guests as $guest)
                            <tr class="hover:bg-primary-light/50 transition-colors duration-200">
                                <td class="py-3 px-4 border-b text-primary-dark">
                                    {{ $guest->name }}<br>
                                    <span class="text-sm text-gray-500">{{ $guest->phone_number }}</span>
                                </td>
                                <td class="py-3 px-4 border-b text-center relative">
                                    <div class="relative inline-block">
                                        <button 
                                            onclick="toggleDropdown(
                                            this,
                                            '{{ $guest->slug }}',
                                            '{{ $guest->name }}',
                                            'Pernikahan Jack & Rose',
                                            '{{ $guest->guest_type }}',
                                            '{{ route('guests.show', $guest->slug) }}',
                                            '{{ $guest->photo ? asset('storage/'.$guest->photo) : '' }}'
                                            )"
                                            class="px-3 py-1 bg-primary text-white rounded shadow hover:bg-primary-dark focus:ring-2 focus:ring-primary-light">
                                            <i class="fa-solid fa-cog"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <!-- Pagination Links -->
                <div id="pagination-container" class="mt-4 flex justify-center">
                    {{ $guests->appends(request()->query())->links() }}
                </div>
            </div>
            
            <!-- Action Dropdown Container -->
            <div id="dropdown-container"
                class="hidden absolute right-0 bg-white border rounded-lg shadow-md mt-2 text-sm z-50 w-48">
                <ul id="dropdown-actions">
                    <!-- Actions akan di-inject lewat JS -->
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Modal Foto -->
<div id="photoModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4">Foto Tamu</h2>
        <img id="modal-photo" src="" alt="Foto Tamu" class="w-full h-auto mb-4">
        <button id="close-modal-btn" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
            Tutup
        </button>
    </div>
</div>

<script>
  // Toggle Dropdown Function
  function toggleDropdown(button, guestSlug, guestName, eventName, vipType, linkToInvitation, photoUrl) {
      const dropdown = document.getElementById('dropdown-container');
      const rect = button.getBoundingClientRect();

      // Posisi dropdown mengikuti posisi tombol
      dropdown.style.top = `${rect.bottom + window.scrollY}px`;
      dropdown.style.left = `${rect.left + window.scrollX}px`;
      dropdown.classList.toggle('hidden');

      // Template pesan WhatsApp
      const messageTemplate = `
Kepada Yth.
Bapak/Ibu/Saudara/i
*${guestName}*
_____________________

Tanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i
untuk menghadiri acara pernikahan kami.

*Berikut link undangan kami* untuk info lengkap acara:
${linkToInvitation}

Merupakan suatu kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i
berkenan untuk hadir dan memberikan doa restu.

Terima Kasih

Hormat kami,

*NB:*
* Mohon tunjukan *QRCode* ini sebagai akses masuk ke acara
* Mohon abaikan jika nama yg dituju bukan *${guestName}*

Tipe: ${vipType}
      `;
      // Encode template agar menjadi format URL
      const encodedMessage = encodeURIComponent(messageTemplate);
      const whatsappLink = `https://wa.me/?text=${encodedMessage}`;

      // Susun item dropdown
      const actions = [
        {
          route: `/${guestSlug}`,
          icon: 'fa-solid fa-eye',
          label: 'Lihat Halaman'
        },
        {
          route: `/photo/${guestSlug}`,
          icon: 'fa-solid fa-camera',
          label: 'Ambil Foto'
        },
        // Tambahkan aksi "Lihat Foto" di sini
        // Panggil fungsi JS `openPhotoModal(photoUrl)` jika `photoUrl` tidak kosongphotoUrl ? 
        {
          route: '#',
          icon: 'fa-solid fa-image',
          label: 'Lihat Foto',
          onclick: `openPhotoModal('${photoUrl}')`
        },
        {
          route: `/guests/${guestSlug}/edit`,
          icon: 'fa-solid fa-pen',
          label: 'Edit'
        },
        {
          route: `/guests/${guestSlug}`,
          icon: 'fa-solid fa-trash',
          label: 'Hapus',
          method: 'DELETE'
        },
        {
          route: whatsappLink,
          icon: 'fa-brands fa-whatsapp',
          label: 'Kirim Undangan'
        }
      ].filter(Boolean); // filter(Boolean) untuk menghilangkan null jika photoUrl kosong

      const dropdownActions = document.getElementById('dropdown-actions');
      dropdownActions.innerHTML = actions.map(action => {
        if (action.method) {
          // Untuk aksi DELETE (method="DELETE")
          return `
            <li>
              <form action="${action.route}" method="POST"
                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus tamu ini?')">
                  @csrf
                  @method('${action.method}')
                  <button type="submit"
                          class="block w-full text-left px-4 py-2 text-danger hover:bg-red-100 hover:text-red-600">
                      <i class="${action.icon} mr-2"></i>${action.label}
                  </button>
              </form>
            </li>
          `;
        } else if (action.onclick) {
          // Aksi button custom (misal Lihat Foto)
          return `
            <li>
              <button onclick="${action.onclick}"
                      class="block w-full text-left px-4 py-2 text-primary hover:bg-primary-light hover:text-primary-dark">
                  <i class="${action.icon} mr-2"></i>${action.label}
              </button>
            </li>
          `;
        } else {
          // Aksi GET biasa (link)
          return `
            <li>
              <a href="${action.route}" 
                 class="block px-4 py-2 text-primary hover:bg-primary-light hover:text-primary-dark">
                  <i class="${action.icon} mr-2"></i>${action.label}
              </a>
            </li>
          `;
        }
      }).join('');
  }

  // Fungsi untuk membuka modal foto
  function openPhotoModal(photoUrl) {
      const modal = document.getElementById('photoModal');
      const modalPhoto = document.getElementById('modal-photo');

      // Set src gambar ke photoUrl
      modalPhoto.src = photoUrl;
      // Tampilkan modal
      modal.classList.remove('hidden');
  }

  // AJAX Search and Sort Implementation
  document.addEventListener('DOMContentLoaded', function() {
      initializeSearchAndSort();
      
      // Tombol close modal
      document.getElementById('close-modal-btn').addEventListener('click', () => {
          document.getElementById('photoModal').classList.add('hidden');
      });
      
      // Menutup dropdown ketika klik di luar
      document.addEventListener('click', function(event) {
          const dropdown = document.getElementById('dropdown-container');
          if (dropdown && !dropdown.contains(event.target) && !event.target.closest('button[onclick^="toggleDropdown"]')) {
              dropdown.classList.add('hidden');
          }
          
          // Close sort dropdown when clicking outside
          const sortDropdown = document.getElementById('sort-dropdown');
          const urutkanBtn = document.getElementById('urutkan-button');
          if (sortDropdown && !urutkanBtn.contains(event.target) && !sortDropdown.contains(event.target)) {
              sortDropdown.classList.add('hidden');
          }
      });
  });

  // Initialize search and sort functionality
  function initializeSearchAndSort() {
      const searchInput = document.getElementById('search-input');
      const searchBtn = document.getElementById('search-btn');
      const urutkanBtn = document.getElementById('urutkan-button');
      const sortDropdown = document.getElementById('sort-dropdown');
      const sortOptions = document.querySelectorAll('.sort-option');
      
      // Global variables to keep track of current state
      window.currentSort = window.currentSort || 'name';
      window.currentDirection = window.currentDirection || 'asc';
      window.currentPage = window.currentPage || 1;
      window.currentSearch = window.currentSearch || '';
      
      // Toggle Sort Dropdown
      if (urutkanBtn) {
          urutkanBtn.addEventListener('click', function(e) {
              e.preventDefault();
              e.stopPropagation();
              if (sortDropdown) {
                  sortDropdown.classList.toggle('hidden');
              }
          });
      }
      
      // Handle Sort Options Click
      if (sortOptions) {
          sortOptions.forEach(option => {
              option.addEventListener('click', function(e) {
                  e.preventDefault();
                  e.stopPropagation();
                  
                  window.currentSort = this.getAttribute('data-sort');
                  window.currentDirection = this.getAttribute('data-dir');
                  
                  if (sortDropdown) {
                      sortDropdown.classList.add('hidden');
                  }
                  
                  // Fetch sorted data
                  fetchGuests();
              });
          });
      }
      
      // Handle Search Click
      if (searchBtn) {
          searchBtn.addEventListener('click', function(e) {
              e.preventDefault();
              if (searchInput) {
                  window.currentSearch = searchInput.value;
              }
              window.currentPage = 1; // Reset to first page when doing a new search
              fetchGuests();
          });
      }
      
      // Also enable Enter key to search
      if (searchInput) {
          searchInput.addEventListener('keypress', function(e) {
              if (e.key === 'Enter') {
                  e.preventDefault();
                  window.currentSearch = searchInput.value;
                  window.currentPage = 1;
                  fetchGuests();
              }
          });
      }
  }
      
  // Function to fetch guests with current parameters
  function fetchGuests() {
      // Show loading indicator
      document.getElementById('guest-table-container').innerHTML = '<div class="p-8 text-center"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><p class="mt-2">Mengambil data tamu...</p></div>';
      
      // Construct the URL with query parameters
      const url = `{{ route('guests.ajax') }}?search=${encodeURIComponent(window.currentSearch)}&sort=${window.currentSort}&direction=${window.currentDirection}&page=${window.currentPage}`;
      
      fetch(url)
          .then(response => response.json())
          .then(data => {
              updateGuestsTable(data);
          })
          .catch(error => {
              console.error('Error fetching guests:', error);
              document.getElementById('guest-table-container').innerHTML = '<div class="p-8 text-center text-red-600">Gagal mengambil data. Silakan coba lagi.</div>';
          });
  }
  
  // Function to update the guests table with received data
  function updateGuestsTable(data) {
      // Create table HTML
      let tableHTML = `
          <table class="min-w-full bg-white relative">
              <thead class="bg-primary-dark text-primary-light">
                  <tr>
                      <th class="py-3 px-4 border-b text-start">Nama</th>
                      <th class="py-3 px-4 border-b w-12">
                          <a href="{{ route('guests.create') }}"
                              class="px-4 py-2 bg-primary-white text-bg-primary rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                              <i class="fa-solid fa-user-plus"></i>
                          </a>
                      </th>
                  </tr>
              </thead>
              <tbody id="guests-table-body">
      `;
      
      if (data.data.length === 0) {
          tableHTML += `
              <tr>
                  <td colspan="2" class="py-6 text-center text-gray-500">Tidak ada tamu yang ditemukan.</td>
              </tr>
          `;
      } else {
          data.data.forEach(guest => {
              const photoUrl = guest.photo ? `{{ asset('storage') }}/${guest.photo}` : '';
              
              tableHTML += `
                  <tr class="hover:bg-primary-light/50 transition-colors duration-200">
                      <td class="py-3 px-4 border-b text-primary-dark">
                          ${guest.name}<br>
                          <span class="text-sm text-gray-500">${guest.phone_number || ''}</span>
                      </td>
                      <td class="py-3 px-4 border-b text-center relative">
                          <div class="relative inline-block">
                              <button 
                                  onclick="toggleDropdown(
                                  this,
                                  '${guest.slug}',
                                  '${guest.name}',
                                  'Pernikahan Jack & Rose',
                                  '${guest.guest_type || ''}',
                                  '${`{{ url('/') }}/${guest.slug}`}',
                                  '${photoUrl}'
                                  )"
                                  class="px-3 py-1 bg-primary text-white rounded shadow hover:bg-primary-dark focus:ring-2 focus:ring-primary-light">
                                  <i class="fa-solid fa-cog"></i>
                              </button>
                          </div>
                      </td>
                  </tr>
              `;
          });
      }
      
      tableHTML += `
              </tbody>
          </table>
      `;
      
      // Add pagination
      tableHTML += `
          <div id="pagination-container" class="mt-4 flex justify-center">
              <nav>
                  <ul class="flex list-none">
      `;
      
      // Previous page link
      if (data.prev_page_url) {
          tableHTML += `
              <li>
                  <a href="#" data-page="${data.current_page - 1}" class="px-3 py-1 mx-1 bg-primary-light text-primary-dark rounded hover:bg-primary-dark hover:text-white">
                      &laquo;
                  </a>
              </li>
          `;
      } else {
          tableHTML += `
              <li>
                  <span class="px-3 py-1 mx-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">
                      &laquo;
                  </span>
              </li>
          `;
      }
      
      // Page numbers
      for (let i = 1; i <= data.last_page; i++) {
          if (i === data.current_page) {
              tableHTML += `
                  <li>
                      <span class="px-3 py-1 mx-1 bg-primary-dark text-white rounded">
                          ${i}
                      </span>
                  </li>
              `;
          } else {
              tableHTML += `
                  <li>
                      <a href="#" data-page="${i}" class="px-3 py-1 mx-1 bg-primary-light text-primary-dark rounded hover:bg-primary-dark hover:text-white">
                          ${i}
                      </a>
                  </li>
              `;
          }
      }
      
      // Next page link
      if (data.next_page_url) {
          tableHTML += `
              <li>
                  <a href="#" data-page="${data.current_page + 1}" class="px-3 py-1 mx-1 bg-primary-light text-primary-dark rounded hover:bg-primary-dark hover:text-white">
                      &raquo;
                  </a>
              </li>
          `;
      } else {
          tableHTML += `
              <li>
                  <span class="px-3 py-1 mx-1 bg-gray-200 text-gray-500 rounded cursor-not-allowed">
                      &raquo;
                  </span>
              </li>
          `;
      }
      
      tableHTML += `
                  </ul>
              </nav>
          </div>
      `;
      
      // Update the container
      document.getElementById('guest-table-container').innerHTML = tableHTML;
      
      // Add event listeners to pagination links
      document.querySelectorAll('#pagination-container a').forEach(link => {
          link.addEventListener('click', function(e) {
              e.preventDefault();
              window.currentPage = parseInt(this.getAttribute('data-page'));
              fetchGuests();
          });
      });
  }
</script>
@endsection