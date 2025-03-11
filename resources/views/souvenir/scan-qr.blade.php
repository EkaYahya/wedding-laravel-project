@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4 bg-primary-light h-screen">
        <h1 class="text-3xl font-bold text-primary-dark text-center mb-6">
            Scan QR Code untuk menerima souvenir
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

    <!-- Modal untuk Menampilkan Hasil -->
    <div id="souvenir-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-8 rounded-lg shadow-xl w-full max-w-md mx-4 text-center">
            <h3 id="modal-title" class="text-2xl font-semibold mb-4"></h3>
            <p id="modal-message" class="text-lg mb-4"></p>
            <button id="close-modal" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                Tutup
            </button>
        </div>
    </div>

    <!-- Script untuk QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
let html5QrCode;
let currentDeviceId = null;
let videoStream = null;
let isScannerRunning = false;
let isProcessing = false; // Tambahkan penanda proses

// Fungsi untuk memproses hasil scan
function onScanSuccess(decodedText, decodedResult) {
    if (isProcessing) return; // Jika sedang memproses, hentikan pemanggilan ulang
    isProcessing = true; // Tandai bahwa proses sedang berjalan

    const url = decodedText;
    const regex = /\/guests\/([a-z0-9\-]+)\/update-attendance/;
    const match = url.match(regex);

    if (match) {
        const slug = match[1];

        // Perbarui status souvenir
        fetch(`/guests/${slug}/update-souvenir`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Gagal memperbarui souvenir. Status: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            const modalTitle = document.getElementById('modal-title');
            const modalMessage = document.getElementById('modal-message');

            if (data.success) {
                modalTitle.textContent = "Pengambilan Souvenir Berhasil!";
                modalMessage.textContent = `Nama Tamu: ${data.guest.name}. Souvenir telah berhasil diambil.`;
            } else if (data.souvenir_taken) {
                modalTitle.textContent = "Voucher Sudah Diambil";
                modalMessage.textContent = "Souvenir ini sudah pernah diambil sebelumnya.";
            } else {
                modalTitle.textContent = "Error";
                modalMessage.textContent = data.message || "Terjadi kesalahan.";
            }

            // Tampilkan modal
            document.getElementById('souvenir-modal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
        })
        .finally(() => {
            stopQrCodeScanner(); // Hentikan scanner setelah scan selesai
            isProcessing = false; // Set penanda kembali ke false
        });
    } else {
        alert('QR Code tidak valid!');
        isProcessing = false; // Reset penanda jika QR code tidak valid
    }
}

// Fungsi untuk menghentikan scanner
function stopQrCodeScanner() {
    if (isScannerRunning) {
        html5QrCode.stop().catch(err => console.error("Error stopping QR Code scanner:", err));
    }

    if (videoStream) {
        videoStream.getTracks().forEach(track => track.stop());
    }

    currentDeviceId = null;
    isScannerRunning = false;
}

// Menutup modal
document.getElementById('close-modal').addEventListener('click', function() {
    document.getElementById('souvenir-modal').classList.add('hidden');
    isProcessing = false; // Reset penanda untuk memulai scan berikutnya
    startQrCodeScanner(); // Mulai ulang scanner jika diperlukan
});

// Fungsi untuk memulai scanner
function startQrCodeScanner() {
    if (isScannerRunning) return;

    if (!currentDeviceId) {
        alert('Pilih kamera terlebih dahulu!');
        return;
    }

    html5QrCode = new Html5Qrcode("reader");

    html5QrCode.start(
        { deviceId: { exact: currentDeviceId } },
        { fps: 10, qrbox: 200 },
        onScanSuccess,
        onScanFailure
    ).then(stream => {
        videoStream = stream;
        isScannerRunning = true;
    }).catch(error => {
        console.error("Error starting QR code scanner:", error);
    });
}

// Fungsi untuk menangani kesalahan scan
function onScanFailure(error) {
    console.warn(`QR scan error: ${error}`);
}

// Jalankan saat halaman dimuat
window.onload = function() {
    navigator.mediaDevices.enumerateDevices()
        .then(devices => {
            const cameras = devices.filter(device => device.kind === 'videoinput');
            const cameraSelect = document.getElementById('camera-select');
            cameraSelect.innerHTML = '<option value="">Pilih Kamera</option>';

            cameras.forEach(device => {
                if (device.label) {
                    const option = document.createElement('option');
                    option.value = device.deviceId;
                    option.text = device.label;
                    cameraSelect.appendChild(option);
                }
            });

            cameraSelect.addEventListener('change', function() {
                if (this.value) {
                    currentDeviceId = this.value;
                    startQrCodeScanner();
                } else {
                    stopQrCodeScanner();
                }
            });
        })
        .catch(err => {
            console.error('Error accessing devices:', err);
            alert('Terjadi kesalahan saat mengakses perangkat kamera.');
        });
};

    </script>
@endsection
