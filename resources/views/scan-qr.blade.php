@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 bg-primary-light h-screen">
        <h1 class="text-3xl font-bold text-primary-dark text-center mb-6">
            Scan QR Code untuk Kehadiran Tamu
        </h1>

        <!-- Dropdown untuk memilih kamera -->
        <div class="mb-4">
            <label for="camera-select" class="block text-lg font-medium mb-2">Pilih Kamera</label>
            <select id="camera-select" class="w-full p-2 border rounded">
                <option value="">Pilih Kamera</option>
            </select>
        </div>

        <!-- QR Code Scanner -->
         <div class="container mx-auto w-full h-max bg-gray-200 rounded-xl">
             <div id="reader" class="w-full h-full bg-gray-200 rounded-xl"></div>
        </div>
    </div>

    <!-- Modal untuk Menampilkan Hasil Kehadiran -->
    <div id="attendance-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-lg mx-4 text-center">
            <h3 class="text-2xl font-semibold mb-4">Kehadiran Tamu Berhasil Diperbarui!</h3>
            <p id="guest-name" class="text-xl mb-2"></p>
            <p id="guest-type" class="text-xl mb-2"></p>
            <p id="guest-count" class="text-lg"></p>
            <!-- Tombol untuk membuka modal -->
            <button id="openConfirmationModal" class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 w-full">
                Update Konfirmasi Kehadiran
            </button>
            <button id="close-modal" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Tutup
            </button>
            <!-- Tombol untuk mencetak QR Code -->
            <button id="printQrButton" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Cetak QR Code
            </button>
        </div>
    </div>
    
    <!-- Modal Konfirmasi Kehadiran -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md mx-4">
            <h3 class="text-xl font-semibold mb-4">Update Konfirmasi Kehadiran</h3>
            <form id="confirmation-form" class="space-y-4">
                @csrf
                @method('PUT')
                <label for="will_attend" class="block text-lg">Apakah Anda Akan Hadir?</label>
                <select id="will_attend" name="will_attend" class="w-full p-2 border border-gray-300 rounded">
                    <option value="1">Ya</option>
                    <option value="0">Tidak</option>
                </select>
                
                <label for="number_of_guests" class="block text-lg">Jumlah Orang Yang Bersama Anda?</label>
                <select id="number_of_guests" name="number_of_guests" class="w-full p-2 border border-gray-300 rounded"></select>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" id="closeConfirmationModal" class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Konfirmasi Kehadiran
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Script untuk QR Code Scanner -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        let html5QrCode;
        let currentDeviceId = null;
        let videoStream = null;
        let isScannerRunning = false; 

        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('attendance-modal').classList.add('hidden');
        });
        
        document.getElementById('openConfirmationModal').addEventListener('click', function() {
            document.getElementById('confirmationModal').classList.remove('hidden');
        });
        
        document.getElementById('closeConfirmationModal').addEventListener('click', function() {
            document.getElementById('confirmationModal').classList.add('hidden');
        });

        // Menangani pemindaian QR Code
        function onScanSuccess(decodedText, decodedResult) {
            const url = decodedText;
            const regex = /\/guests\/([a-z0-9\-]+)\/update-attendance/;
            const match = url.match(regex);
        
            if (match) {
                // Hentikan kamera setelah scan berhasil
                stopQrCodeScanner();
        
                const slug = match[1];  // Ambil slug dari URL yang dipindai
        
                // Menggunakan fetch untuk melakukan request PUT ke server
                fetch(`/guests/${slug}/update-attendance`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        attended: true
                    })
                })
                .then(async response => {
                    const data = await response.json();
                    
                    if (data.success) {
                        // Tampilkan modal dengan informasi tamu
                        document.getElementById('guest-name').textContent = `Nama Tamu: ${data.guest.name}`;
                        document.getElementById('guest-type').textContent = `Tipe Tamu: ${data.guest.guest_type}`;
                        document.getElementById('will_attend').value = data.guest.will_attend ? '1' : '0';

                        // TAMBAHKAN KODE INI: Simpan nama tamu ke localStorage
                        localStorage.setItem('guestName', data.guest.name);
                        localStorage.setItem('guestType', data.guest.guest_type);
                        
                        // Broadcast ke tab lain jika ada
                        window.postMessage({ 
                            type: 'GUEST_UPDATED', 
                            guestName: data.guest.name 
                        }, '*');
                        
                        const guestCount = data.guest.number_of_guests;
                        const numberOfGuestsSelect = document.getElementById('number_of_guests');
        
                        // Pastikan dropdown di-reset sepenuhnya
                        while (numberOfGuestsSelect.firstChild) {
                            numberOfGuestsSelect.removeChild(numberOfGuestsSelect.firstChild);
                        }
                        
                        // Isi ulang dropdown
                        for (let i = 1; i <= 5; i++) {
                            const option = document.createElement('option');
                            option.value = i;
                            option.textContent = i;
                            if (i === guestCount) {
                                option.selected = true;
                            }
                            numberOfGuestsSelect.appendChild(option);
                        }
        
                        // Tampilkan modal
                        document.getElementById('attendance-modal').classList.remove('hidden');

                        // Set href for print QR button
                        document.getElementById('printQrButton').onclick = function() {
                            window.location.href = `/guests/${slug}/print-qr`;
                        };
                    } else if (response.status === 400) {
                        alert(data.message);
                    } else {
                        alert('Tamu tidak ditemukan!');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan: ' + error.message);
                });
        
                // Form konfirmasi kehadiran
                document.getElementById("confirmation-form").addEventListener("submit", function(event) {
                    event.preventDefault(); // Mencegah pengiriman form secara default
        
                    const willAttend = document.getElementById("will_attend").value;
                    const numberOfGuests = document.getElementById("number_of_guests").value;
        
                    fetch(`/api/guests/${slug}/rsvp`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify({
                            will_attend: willAttend,
                            number_of_guests: numberOfGuests
                        })
                    })
                    .then(async response => {
                        const data = await response.json();
                        if (!response.ok) throw new Error(data.message || "Terjadi kesalahan");
        
                        alert("Konfirmasi Kehadiran Berhasil Diperbarui!");
                        document.getElementById("confirmationModal").classList.add("hidden"); // Tutup modal setelah sukses
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Terjadi kesalahan: " + error.message);
                    });
                });
            } else {
                alert('QR Code tidak valid!');
            }
        }

        function onScanFailure(error) {
            console.warn(`QR scan error: ${error}`);
        }

        // Menutup modal
        document.getElementById('close-modal').addEventListener('click', function() {
            document.getElementById('attendance-modal').classList.add('hidden');
        });

        // Mengambil daftar kamera yang tersedia
        function getCameras() {
            navigator.mediaDevices.enumerateDevices()
                .then(devices => {
                    const cameras = devices.filter(device => device.kind === 'videoinput');
                    const cameraSelect = document.getElementById('camera-select');

                    // Kosongkan dropdown sebelum mengisi
                    cameraSelect.innerHTML = '<option value="">Pilih Kamera</option>';

                    // Masukkan kamera yang ditemukan ke dropdown
                    cameras.forEach(device => {
                        if (device.label) {
                            const option = document.createElement('option');
                            option.value = device.deviceId;
                            option.text = device.label;
                            cameraSelect.appendChild(option);
                        }
                    });

                    // Tambahkan event listener untuk perubahan pilihan kamera
                    cameraSelect.addEventListener('change', function() {
                        console.log('Pilihan kamera berubah:', this.value);
                        if (this.value) {
                            currentDeviceId = this.value;
                            startQrCodeScanner();
                        } else {
                            stopQrCodeScanner(); // Matikan pemindaian dan video ketika memilih opsi default
                        }
                    });
                })
                .catch(err => {
                    console.error('Error accessing devices:', err);
                    alert('Terjadi kesalahan saat mengakses perangkat kamera.');
                });
        }

        // Mengambil izin untuk mengakses kamera dan memulai pengambilan daftar kamera
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                stream.getTracks().forEach(track => track.stop());
                getCameras();
            })
            .catch(err => {
                console.error('Izin kamera ditolak:', err);
                alert('Mohon izinkan akses ke kamera untuk menggunakan fitur ini.');
            });

        // Memulai pemindaian QR Code dengan kamera yang dipilih
        function startQrCodeScanner() {
            if (isScannerRunning) {
                html5QrCode.stop(); // Stop kamera yang sedang berjalan
            }

            if (!currentDeviceId) {
                alert('Pilih kamera terlebih dahulu!');
                return;
            }

            html5QrCode = new Html5Qrcode("reader");

            html5QrCode.start(
                { deviceId: { exact: currentDeviceId } },  // Menggunakan deviceId kamera yang dipilih
                { fps: 10, qrbox: 200 },  // Mengatur frame per second dan ukuran kotak pemindaian
                onScanSuccess,
                onScanFailure
            ).then((stream) => {
                // Store the media stream for later use
                const videoElement = document.querySelector("#reader video");
                const qrShadedRegion = document.getElementById('qr-shaded-region');
                if (videoElement) {
                    // Memberikan kelas 'rounded-2xl' menggunakan Tailwind CSS
                    videoElement.classList.add("rounded-2xl");
                }

                if(qrShadedRegion) {
                    // Memberikan kelas 'rounded-2xl' menggunakan Tailwind CSS
                    qrShadedRegion.style.bordercolor = 'transparent'; 
                }
                videoStream = stream;
            })
            .catch((error) => {
                console.error("Error starting QR code scanner:", error);
            });

            isScannerRunning = true; 
        }

        function stopQrCodeScanner() {
            if (isScannerRunning) {
                html5QrCode.stop();  // Stop kamera yang sedang berjalan
            }

            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());  // Matikan stream video
            }

            // Setel currentDeviceId ke null untuk menunjukkan bahwa kamera dimatikan
            currentDeviceId = null;
            isScannerRunning = false;
        }

        // Mulai mengambil daftar kamera saat halaman dimuat
        window.onload = function() {
            getCameras();
            console.log('halaman dimuat');
        };

        document.addEventListener('DOMContentLoaded', function () {
            const updateButton = document.querySelector('[data-target="#confirmationModal"]');
            const confirmationModal = document.getElementById('confirmationModal');

            if (updateButton && confirmationModal) {
                updateButton.addEventListener('click', function () {
                    confirmationModal.classList.add('show');
                    confirmationModal.style.display = 'block';
                    document.body.classList.add('modal-open');
                });

                confirmationModal.querySelector('.close').addEventListener('click', function () {
                    confirmationModal.classList.remove('show');
                    confirmationModal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                });
            }
        });
    </script>
@endsection