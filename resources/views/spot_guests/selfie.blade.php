@extends('layouts.app')

@section('title', 'Ambil Selfie')

@section('content')
<div class="bg-primary-light min-h-screen py-8 pb-20">
    <div class="container mx-auto px-6">
        <h2 class="text-3xl font-bold text-primary-dark mb-6">Ambil Selfie</h2>

        @if (session('error'))
            <div class="alert alert-danger bg-red-100 text-red-800 p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded-lg shadow mb-8">
            <div class="text-center">
                <p class="mb-4 text-primary-dark">Silakan ambil selfie untuk melanjutkan pendaftaran</p>
                
                <div class="flex flex-col items-center">
                    <!-- Video element for camera access -->
                    <video id="camera" class="w-full max-w-md h-auto border-4 border-primary rounded-lg mb-4" autoplay></video>
                    
                    <!-- Canvas for captured photo -->
                    <canvas id="canvas" class="w-full max-w-md h-auto border-4 border-primary rounded-lg mb-4 hidden"></canvas>
                    
                    <!-- Image preview -->
                    <div id="photo-preview" class="w-full max-w-md mb-4 hidden">
                        <img id="captured-photo" class="w-full border-4 border-primary rounded-lg" src="" alt="Captured photo">
                    </div>
                    
                    <!-- Action buttons -->
                    <div class="flex flex-wrap justify-center gap-4 mt-4">
                        <button id="capture-btn" class="px-6 py-3 bg-primary-dark text-white rounded-full shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-1.121-1.121A2 2 0 0011.172 3H8.828a2 2 0 00-1.414.586L6.293 4.707A1 1 0 015.586 5H4zm6 9a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                                Ambil Foto
                            </span>
                        </button>
                        
                        <button id="retake-btn" class="px-6 py-3 bg-gray-500 text-white rounded-full shadow hover:bg-gray-600 focus:ring-2 focus:ring-gray-300 hidden">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                </svg>
                                Ambil Ulang
                            </span>
                        </button>
                        
                        <button id="continue-btn" class="px-6 py-3 bg-green-600 text-white rounded-full shadow hover:bg-green-700 focus:ring-2 focus:ring-green-300 hidden">
                            <span class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Lanjutkan
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Input form for after selfie is taken -->
        <form id="guest-form" action="{{ isset($spotGuest) ? route('spot-guests.update', $spotGuest->slug) : route('spot-guests.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow hidden">
            @csrf
            @if(isset($spotGuest))
                @method('PUT')
            @endif
            
            <!-- Hidden input for the photo data -->
            <input type="hidden" name="photo_data" id="photo-data">
            
            <!-- Input Nama -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-primary-dark">Nama Tamu</label>
                <input type="text" name="name" id="name" value="{{ $spotGuest->name ?? old('name') }}" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" required>
            </div>
            
            <!-- Input Nomor WA -->
            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-primary-dark">Nomor WA</label>
                <input type="tel" name="phone_number" id="phone_number" value="{{ $spotGuest->phone_number ?? old('phone_number') }}" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark" required>
            </div>

            <!-- Dropdown Jenis Tamu -->
            <div class="mb-4">
                <label for="guest_type" class="block text-sm font-medium text-primary-dark">Jenis Tamu</label>
                <select name="guest_type" id="guest_type" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark">
                    <option value="VIP" {{ (isset($spotGuest) && $spotGuest->guest_type == 'VIP') ? 'selected' : '' }}>VIP</option>
                    <option value="Regular" {{ (isset($spotGuest) && $spotGuest->guest_type == 'Regular') ? 'selected' : '' }}>Regular</option>
                    <option value="Special" {{ (isset($spotGuest) && $spotGuest->guest_type == 'Special') ? 'selected' : '' }}>Special</option>
                    <option value="" {{ (isset($spotGuest) && !in_array($spotGuest->guest_type, ['VIP', 'Regular', 'Special'])) ? 'selected' : '' }}>Masukkan Jenis Tamu Baru...</option>
                </select>
            </div>

            <!-- Input Custom Jenis Tamu (Jika diperlukan) -->
            <div class="mb-4" id="custom-guest-type-container" style="{{ (isset($spotGuest) && !in_array($spotGuest->guest_type, ['VIP', 'Regular', 'Special'])) ? 'display: block;' : 'display: none;' }}">
                <label for="custom_guest_type" class="block text-sm font-medium text-primary-dark">Jenis Tamu Lainnya</label>
                <input type="text" name="custom_guest_type" id="custom_guest_type" value="{{ (isset($spotGuest) && !in_array($spotGuest->guest_type, ['VIP', 'Regular', 'Special'])) ? $spotGuest->guest_type : '' }}" class="w-full mt-1 p-2 border border-gray-300 rounded focus:ring-primary-light focus:border-primary-dark">
            </div>
            
            <!-- Tombol Simpan -->
            <button type="submit" class="px-4 py-2 bg-primary-dark text-white rounded shadow hover:bg-primary focus:ring-2 focus:ring-primary-light">
                {{ isset($spotGuest) ? 'Perbarui' : 'Simpan' }}
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const video = document.getElementById('camera');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('capture-btn');
        const retakeBtn = document.getElementById('retake-btn');
        const continueBtn = document.getElementById('continue-btn');
        const photoPreview = document.getElementById('photo-preview');
        const capturedPhoto = document.getElementById('captured-photo');
        const photoData = document.getElementById('photo-data');
        const guestForm = document.getElementById('guest-form');
        
        let stream;
        
        // Access webcam
        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Tidak dapat mengakses kamera. Pastikan kamera tersedia dan Anda telah memberikan izin.');
            }
        }
        
        // Start the camera when page loads
        startCamera();
        
        // Capture photo
// Capture photo
captureBtn.addEventListener('click', function() {
    // Set canvas dimensions to match video
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    
    // Draw the video frame to the canvas
    canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Display the captured photo on preview
    const photoUrl = canvas.toDataURL('image/png');
    capturedPhoto.src = photoUrl;
    
    // Toggle UI elements
    video.classList.add('hidden');
    photoPreview.classList.remove('hidden');
    captureBtn.classList.add('hidden');
    retakeBtn.classList.remove('hidden');
    continueBtn.classList.remove('hidden');
    
    // Convert canvas to blob for form submission
    canvas.toBlob(function(blob) {
        // Create a File object from Blob
        const photoFile = new File([blob], "selfie.png", { type: "image/png" });
        
        // Create a DataTransfer object to create a FileList
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(photoFile);
        
        // Create a file input if it doesn't exist yet
        if (!document.getElementById('selfie-file-input')) {
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.id = 'selfie-file-input';
            fileInput.name = 'photo';
            fileInput.style.display = 'none';
            document.getElementById('guest-form').appendChild(fileInput);
        }
        
        // Set the FileList to the file input
        document.getElementById('selfie-file-input').files = dataTransfer.files;
    }, 'image/png');
});
                
        // Retake photo
        retakeBtn.addEventListener('click', function() {
            // Reset canvas
            canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            
            // Reset form fields
            photoData.value = '';
            
            // Toggle UI elements
            video.classList.remove('hidden');
            canvas.classList.add('hidden');
            photoPreview.classList.add('hidden');
            captureBtn.classList.remove('hidden');
            retakeBtn.classList.add('hidden');
            continueBtn.classList.add('hidden');
            guestForm.classList.add('hidden');
        });
        
        // Continue to form after taking photo
        continueBtn.addEventListener('click', function() {
            // Show the form
            guestForm.classList.remove('hidden');
            
            // Scroll to the form
            guestForm.scrollIntoView({ behavior: 'smooth' });
        });
        
        // Handle custom guest type
        document.getElementById('guest_type').addEventListener('change', function() {
            const customGuestTypeContainer = document.getElementById('custom-guest-type-container');
            const guestType = this.value;
            
            // If empty option selected, show custom input
            if (guestType === "") {
                customGuestTypeContainer.style.display = 'block';
            } else {
                customGuestTypeContainer.style.display = 'none';
            }
        });
    });
</script>
@endsection