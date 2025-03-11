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

    <div class="container mx-auto px-6 pb-20 mt-5">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-bold text-primary-dark">Data Tamu</h2>
        </div>
        <!-- Tombol Ekspor PDF dan Excel -->
        <div class="flex justify-start items-center mb-4">
            <a href="{{ route('guests.exportPDF') }}"
               class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light mr-2">
                Export PDF
            </a>
            <a href="{{ route('guests.exportExcel') }}"
               class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                Export Excel
            </a>
        </div>

        <!-- Tabel Tamu -->
        <div class="bg-white shadow rounded-lg overflow-hidden" style = "border-bottom: 40px solid white;">
            <div class="p-4 bg-primary-dark">
                <form id="search-form" action="{{ route('guests.index') }}" method="GET" class="flex items-center gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tamu..."
                           class="px-4 py-2 border rounded-lg w-full focus:ring-2 focus:ring-primary outline-none"
                           aria-label="Cari tamu">
                    <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition focus:ring-2 focus:ring-primary-light"
                            aria-label="Cari">
                        Cari
                    </button>
                </form>
            </div>
            <!-- Bagian Tabel -->
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
            <tbody>
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
            <!-- Dropdown Container -->
            <!-- Dropdown Container -->
            <div id="dropdown-container"
                class="hidden absolute right-0 bg-white border rounded-lg shadow-md mt-2 text-sm z-50 w-48">
                <ul id="dropdown-actions">
                    <!-- Actions akan di-inject lewat JS -->
                </ul>
            </div>
            <!-- Pagination Links -->
            <div class="mt-4 flex justify-center">
                {{ $guests->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Foto -->

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
  /**
   * toggleDropdown(button, guestSlug, guestName, eventName, vipType, linkToInvitation, photoUrl)
   */
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
{{ $undangan->nama_pasangan }}

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

  // Menutup dropdown ketika klik di luar
  document.addEventListener('click', function(event) {
      const dropdown = document.getElementById('dropdown-container');
      if (!dropdown.contains(event.target) && !event.target.closest('button')) {
          dropdown.classList.add('hidden');
      }
  });

  // Fungsi untuk membuka modal foto
  function openPhotoModal(photoUrl) {
      const modal = document.getElementById('photoModal');
      const modalPhoto = document.getElementById('modal-photo');

      // Set src gambar ke photoUrl
      modalPhoto.src = photoUrl;
      // Tampilkan modal
      modal.classList.remove('hidden');
  }

  // Tombol close modal
  document.getElementById('close-modal-btn').addEventListener('click', () => {
      document.getElementById('photoModal').classList.add('hidden');
  });
</script>

@endforeach
@endsection
