@extends('layouts.app')

@section('content')
<div class="p-6 bg-primary-light min-h-screen py-20">
    <!-- Header Section -->
    <div class="flex items-center justify-between bg-white shadow-md rounded-lg px-6 py-4 mb-6">
        <h1 class="text-lg font-semibold text-gray-800">Settings</h1>
        <button class="bg-orange-400 text-white px-4 py-2 rounded-lg shadow-md hover:bg-orange-500 transition">
            Akun Saya
        </button>
    </div>

    <!-- Total Events -->
    <div class="bg-blue-600 text-white text-center rounded-lg p-4 mb-6 shadow-md">
        <h2 class="text-lg font-semibold">
            Total Event: 
            <span class="font-bold">{{ $settings_events->count() }}</span>
        </h2>
    </div>

    <!-- Looping Event Card -->
@foreach($settings_events as $event)
<div class="bg-white shadow-md rounded-lg p-4 mb-4 flex flex-col sm:flex-row gap-4">
    <!-- Event Image (rasio 16:9) -->
    <div class="aspect-w-16 aspect-h-9 w-full sm:w-2/5 lg:w-1/3 rounded-lg overflow-hidden relative">
        <img
            src="{{ $event->image_url ?? 'https://via.placeholder.com/250x150' }}"
            alt="Event Thumbnail"
            class="w-full h-full object-cover"
        />
        <button
            id="edit-image-button-{{ $event->id }}"
            data-event-id="{{ $event->id }}"
            class="absolute inset-0 bg-black bg-opacity-50 text-white flex items-center justify-center opacity-0 hover:opacity-100 transition"
        >
            Edit Gambar
        </button>
    </div>

    <!-- Event Details + Dropdown -->
    <div class="flex-1 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <!-- Detail Teks -->
        <div>
            <!-- Ganti event_name kalau mau beda tampilan (mis. "Glen & Ismi") -->
            <h3 class="text-xl font-semibold text-gray-800">{{ $event->event_name }}</h3>
            
            <p class="text-gray-600">
                <span class="font-bold">User:</span> {{ $event->user_name }}
            </p>
            <p class="text-gray-600">
                <span class="font-bold">Tanggal:</span>
                {{ \Carbon\Carbon::parse($event->event_date)->format('d/m/Y') }}
            </p>
            <p class="text-gray-600">
                <span class="font-bold">Undangan:</span> {{ $event->invitation_count }}
            </p>
            <a
                href="{{ $event->invitation_link }}"
                class="text-blue-600 hover:underline"
                target="_blank"
            >
                {{ $event->invitation_link }}
            </a>
        </div>

        <!-- Edit Button with Dropdown -->
        <div class="relative">
            <button
                id="edit-dropdown-toggle-{{ $event->id }}"
                class="edit-dropdown-toggle flex h-10 items-center bg-orange-400 text-white px-4 py-2 rounded-lg shadow-md hover:bg-orange-500 transition"
            >
                <i class="fas fa-cog"></i>
            </button>
            <div
                id="edit-dropdown-menu-{{ $event->id }}"
                class="edit-dropdown-menu absolute right-0 mt-2 w-48 bg-white shadow-lg rounded-md hidden z-10"
            >
                <a
                    href="#"
                    data-event-id="{{ $event->id }}"
                    class="open-edit-modal flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100 transition"
                >
                    <i class="fas fa-edit mr-2"></i> Edit
                </a>
                <!-- <a
                    href="#"
                    class="open-wa-template flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100 transition"
                >
                    <i class="fas fa-envelope mr-2"></i> Template WA
                </a> -->
                <a
                    href="{{ route('undangan.edit') }}"
                    class="flex items-center px-4 py-2 text-gray-800 hover:bg-gray-100 transition"
                >
                    <i class="fas fa-tv mr-2"></i> Edit Tamu
                </a>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Edit Modal -->
<div
    id="edit-modal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50"
>
    <div class="bg-white rounded-lg w-96 p-6 relative shadow-lg">
        <!-- Header -->
        <h2 class="text-lg font-bold mb-4 text-gray-800">Edit Event</h2>
        <!-- Form -->
        <form id="edit-form" action="{{ route('settings.updateEvent') }}" method="POST">
            @csrf
            {{-- Gunakan PUT jika ingin update, misalnya: --}}
            {{-- <input type="hidden" name="_method" value="PUT"> --}}
            
            <input type="hidden" name="event_id" id="event_id" />
            <div class="mb-4">
                <label for="edit_event_name" class="block text-sm font-medium text-gray-700">Event</label>
                <input
                    type="text"
                    id="edit_event_name"
                    name="event_name"
                    class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Ex: The Wedding of ..."
                />
            </div>

            <div class="mb-4">
                <label for="edit_user_name" class="block text-sm font-medium text-gray-700">Name Event</label>
                <input
                    type="text"
                    id="edit_user_name"
                    name="user_name"
                    class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 outline-none"
                    placeholder="Ex: Romeo & Juliet"
                />
            </div>

            <div class="mb-6">
                <label for="edit_event_date" class="block text-sm font-medium text-gray-700">Tanggal Event</label>
                <input
                    type="date"
                    id="edit_event_date"
                    name="event_date"
                    class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 outline-none"
                />
            </div>

            <button
                type="submit"
                class="bg-blue-500 text-white w-full py-2 rounded-lg shadow-md hover:bg-blue-600 transition"
            >
                Update
            </button>
            <div class="my-4 flex justify-end w-full">
                <button
                    type="button"
                    id="close-modal"
                    class="bg-gray-400 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-500 transition"
                >
                    Close
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Image Modal -->
<div
    id="edit-image-modal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50"
>
    <div class="bg-white rounded-lg w-96 p-6 relative shadow-lg">
        <!-- Header -->
        <h2 class="text-lg font-bold mb-4 text-gray-800">Edit Photo</h2>

        <!-- Preview -->
        <div class="mb-4">
            <img
                id="image-preview"
                src="{{ $event->image_url ?? 'https://via.placeholder.com/250x150' }}"
                alt="Preview"
                class="w-full h-auto rounded-lg"
            />
        </div>

        <!-- Upload Form -->
        <form 
            id="edit-image-form" 
            action="{{ route('settings.updateImage') }}" 
            method="POST" 
            enctype="multipart/form-data"
        >
            @csrf
            {{-- <input type="hidden" name="_method" value="PUT"> --}}
            <input type="hidden" name="image_event_id" id="image_event_id" />
            <div class="mb-4">
                <input
                    type="file"
                    id="image-upload"
                    name="image_file"
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:outline-none"
                />
            </div>
            <button
                type="submit"
                class="bg-orange-500 text-white w-full py-2 rounded-lg shadow-md hover:bg-orange-600 transition"
            >
                Update
            </button>
            <div class="my-4 flex justify-end w-full">
                <button
                    type="button"
                    id="close-image-modal"
                    class="bg-gray-400 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-500 transition"
                >
                    Close
                </button>
            </div>
        </form>
    </div>
</div>

<!-- WA Template Modal -->
<div
    id="wa-template-modal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50"
>
    <div class="bg-white rounded-lg w-96 p-6 relative shadow-lg">
        <!-- Header -->
        <h2 class="text-lg font-bold mb-4 text-gray-800">Edit Template WA</h2>
        <!-- Form -->
        <form id="wa-template-form" action="{{ route('settings.updateWATemplate') }}" method="POST">
            @csrf
            {{-- <input type="hidden" name="_method" value="PUT"> --}}
            <div class="mb-4">
                <label for="wa-template" class="block text-sm font-medium text-gray-700">Template</label>
                <textarea
                    id="wa-template"
                    name="template_text"
                    class="w-full px-4 py-2 rounded-lg border focus:ring-2 focus:ring-blue-500 outline-none"
                    rows="6"
                >{{ $waTemplate ? $waTemplate->template_text : '' }}</textarea>
            </div>
            <button
                type="submit"
                class="bg-blue-500 text-white w-full py-2 rounded-lg shadow-md hover:bg-blue-600 transition"
            >
                Update Template
            </button>
            <div class="my-4 flex justify-end w-full">
                <button
                    type="button"
                    id="close-wa-modal"
                    class="bg-gray-400 text-white px-4 py-2 rounded-lg shadow-md hover:bg-gray-500 transition"
                >
                    Close
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Handle Dropdown Toggle per Event
        const toggles = document.querySelectorAll('.edit-dropdown-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = toggle.id.replace('edit-dropdown-toggle-', '');
                const menu = document.getElementById(`edit-dropdown-menu-${id}`);
                menu.classList.toggle('hidden');
            });
        });

        document.addEventListener('click', function(e) {
            toggles.forEach(toggle => {
                const id = toggle.id.replace('edit-dropdown-toggle-', '');
                const menu = document.getElementById(`edit-dropdown-menu-${id}`);
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        // Edit Modal
        const editModal = document.getElementById('edit-modal');
        const openEditButtons = document.querySelectorAll('.open-edit-modal');
        const closeModalButton = document.getElementById('close-modal');

        openEditButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const eventId = btn.dataset.eventId;
                // Set hidden input
                document.getElementById('event_id').value = eventId;
                // Tampilkan modal
                editModal.classList.remove('hidden');
            });
        });

        closeModalButton.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });

        // WA Template Modal
        const waModal = document.getElementById('wa-template-modal');
        const openWaButtons = document.querySelectorAll('.open-wa-template');
        const closeWaButton = document.getElementById('close-wa-modal');

        openWaButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                waModal.classList.remove('hidden');
            });
        });

        closeWaButton.addEventListener('click', () => {
            waModal.classList.add('hidden');
        });

        // Edit Image Modal
        const imageModal = document.getElementById('edit-image-modal');
        const editImageButtons = document.querySelectorAll('[id^="edit-image-button-"]');
        const closeImageModalButton = document.getElementById('close-image-modal');

        editImageButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const eventId = btn.dataset.eventId;
                // Set hidden input
                document.getElementById('image_event_id').value = eventId;
                // Tampilkan modal
                imageModal.classList.remove('hidden');
            });
        });

        closeImageModalButton.addEventListener('click', () => {
            imageModal.classList.add('hidden');
        });

        // Menutup modal saat klik di luar area modal
        document.addEventListener('click', function(e) {
            if (!editModal.contains(e.target) && !e.target.closest('.open-edit-modal')) {
                editModal.classList.add('hidden');
            }
            if (!waModal.contains(e.target) && !e.target.closest('.open-wa-template')) {
                waModal.classList.add('hidden');
            }
            if (!imageModal.contains(e.target) && !e.target.closest('[id^="edit-image-button-"]')) {
                imageModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection
