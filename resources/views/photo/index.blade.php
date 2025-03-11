@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 bg-primary-light">
    <h1 class="text-3xl font-bold text-primary-dark text-center mb-6">Ambil Foto</h1>

    <!-- Dropdown untuk memilih perangkat kamera -->
    <div class="flex justify-center mb-4" id="camera-select-container" style="display: none;">
        <select id="camera-select" class="px-4 py-2 rounded border border-gray-300">
            <option value="">Pilih Kamera</option>
        </select>
    </div>

    <!-- Placeholder untuk Kamera -->
    <div class="flex justify-center mb-4">
        <video id="video" width="640" height="480" autoplay></video>
    </div>

    <!-- Tombol untuk mengambil Foto -->
    <div class="text-center">
        <button id="capture-btn" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
            Ambil Foto
        </button>
    </div>

    <!-- Tempat untuk menampilkan hasil Foto -->
    <div class="flex justify-center mt-4" id="photo-preview-container" style="display: none;">
        <canvas id="canvas" width="640" height="480"></canvas>
    </div>

    <!-- Tombol Konfirmasi dan Ulangi Foto -->
    <div id="action-buttons" class="text-center mt-4">
        <button id="confirm-btn" class="px-6 py-2 bg-green-500 text-white rounded hover:bg-green-600">
            Konfirmasi
        </button>
        <button id="retry-btn" class="px-6 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600 ml-4">
            Ulangi Foto
        </button>
    </div>

    <!-- Form untuk mengirim foto ke server -->
    <form id="photo-form" action="{{ route('photo.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="guest_id" value="{{ $guest->id }}">
        <input type="file" id="photo" name="photo" style="display: none;">
        <button type="submit" id="submit-btn" class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600" style="display: none;">
            Simpan Foto
        </button>
    </form>
</div>

<!-- Script untuk menangani kamera dan pengambilan foto -->
<script>
    let videoStream = null;
    let selectedDeviceId = null;

    // Deteksi perangkat mobile
    function isMobileDevice() {
        return /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    }

    // Mendapatkan daftar perangkat kamera dan menambahkannya ke dropdown
    function getCameraDevices() {
        const cameraSelectContainer = document.getElementById('camera-select-container');
        const cameraSelect = document.getElementById('camera-select');

        if (isMobileDevice()) {
            // Jika perangkat mobile, langsung gunakan kamera depan
            startVideoStream({ facingMode: "user" });
        } else {
            // Jika desktop, tampilkan dropdown untuk memilih kamera
            navigator.mediaDevices.enumerateDevices()
                .then(devices => {
                    devices.forEach(device => {
                        if (device.kind === 'videoinput') {
                            const option = document.createElement('option');
                            option.value = device.deviceId;
                            option.textContent = device.label || `Kamera ${cameraSelect.length + 1}`;
                            cameraSelect.appendChild(option);
                        }
                    });

                    // Tampilkan dropdown jika lebih dari satu kamera
                    if (cameraSelect.options.length > 1) {
                        cameraSelectContainer.style.display = 'flex';
                        selectedDeviceId = cameraSelect.options[1].value;
                        startVideoStream({ deviceId: { exact: selectedDeviceId } });
                    }
                })
                .catch(err => {
                    console.error('Error accessing devices:', err);
                    alert('Tidak dapat mengakses perangkat kamera.');
                });
        }
    }

    // Memulai stream video untuk kamera tertentu
    function startVideoStream(constraints) {
        if (videoStream) {
            videoStream.getTracks().forEach(track => track.stop());
        }

        navigator.mediaDevices.getUserMedia({ video: constraints })
            .then(stream => {
                videoStream = stream;
                document.getElementById('video').srcObject = stream;
            })
            .catch(err => {
                console.error('Error accessing camera:', err);
                alert('Terjadi kesalahan saat mengakses kamera.');
            });
    }

    // Menangani perubahan pada dropdown kamera
    document.getElementById('camera-select').addEventListener('change', function () {
        selectedDeviceId = this.value;
        startVideoStream({ deviceId: { exact: selectedDeviceId } });
    });

    // Menangkap foto dari stream video
    document.getElementById('capture-btn').addEventListener('click', function () {
        const canvas = document.getElementById('canvas');
        const context = canvas.getContext('2d');
        const video = document.getElementById('video');

        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        document.getElementById('photo-preview-container').style.display = 'flex';
        document.getElementById('capture-btn').style.display = 'none';
        document.getElementById('action-buttons').style.display = 'block';

        canvas.toBlob(function (blob) {
            const photoInput = document.getElementById('photo');
            const photoFile = new File([blob], "photo.jpg", { type: "image/jpeg" });

            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(photoFile);
            photoInput.files = dataTransfer.files;

            document.getElementById('submit-btn').style.display = 'block';
        }, 'image/jpeg');
    });

    // Mengulang pengambilan foto
    document.getElementById('retry-btn').addEventListener('click', function () {
        document.getElementById('photo-preview-container').style.display = 'none';
        document.getElementById('capture-btn').style.display = 'block';
        document.getElementById('action-buttons').style.display = 'none';
    });

    // Konfirmasi dan simpan foto
    document.getElementById('confirm-btn').addEventListener('click', function () {
        document.getElementById('photo-form').submit();
    });

    // Mulai mengambil video stream saat halaman dimuat
    window.onload = function () {
        getCameraDevices();
    };
</script>
@endsection