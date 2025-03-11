<div id="messageModal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden">
    <div class="bg-white rounded-lg p-6 shadow-md w-1/3">
        <div class="text-center">
            <p class="text-lg">{{ $slot }}</p> <!-- Pesan modal akan dipasukkan di sini -->
            <button id="closeModal" class="mt-4 px-4 py-2 bg-primary-dark text-white rounded hover:bg-primary-light">Tutup</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('messageModal').classList.add('hidden');
    });
</script>