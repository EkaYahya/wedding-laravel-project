@php
use Illuminate\Support\Facades\Cache;

$lastGuest = Cache::get('last_scanned_guest');
$guestType = Cache::get('last_scanned_guest_type');
@endphp
@extends('layouts.guest')
@section('content')
@foreach($settings_events as $event)
<div class="container mx-auto p-4 bg-primary-light h-screen flex flex-col justify-center items-center" style="background-image: url('<?php echo $event->image_url; ?>'); background-size: cover; background-position: center;">
    @if($guestType == 'VIP')
    <div style="max-width: 24rem; background-color: #9c8200; color: #ffffff; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); margin-left: auto; margin-right: auto; margin-top: 5rem;">
    @elseif($guestType == 'Special')
    <div style="max-width: 24rem; background-color: #1a9c00; color: #ffffff; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); margin-left: auto; margin-right: auto; margin-top: 5rem;">
    @else
    <div class="max-w-sm bg-blue-900 text-white rounded-lg shadow-xl mx-auto mt-20">
    @endif
        <div class="p-4">
            <div class="flex flex-col items-center text-center space-y-2">
                <div class="text-sm text-gray-300 uppercase tracking-wider">
                    Selamat Datang
                </div>
                <div id="guest-name" class="text-xl font-semibold animate-fade-in">
                    @if($lastGuest)
                        Yth. Saudara/Saudari {{ $lastGuest }} Beserta Keluarga
                        @if($guestType)
                            <div class="mt-2">
                            @if($guestType == 'VIP')
                                <span style="background-color: #D4AF37; color: #000000; padding: 4px 12px; border-radius: 9999px; font-weight: bold; font-size: 0.875rem; display: inline-block; border: 2px solid #FFD700;">{{ $guestType }}</span>
                            @elseif($guestType == 'Special')
                                <span class="px-3 py-1 rounded-full text-sm bg-green-700 text-green-300 font-bold">{{ $guestType }}</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-sm bg-gray-700 text-gray-300 font-bold">{{ $guestType }}</span>
                            @endif
                            </div>
                        @endif
                    @else
                        Seluruh Tamu Undangan Yang Berbahagia
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
<meta name="csrf-token" content="{{ csrf_token() }}">
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const guestNameElement = document.getElementById('guest-name');
        const defaultMessage = 'Seluruh Tamu Undangan Yang Berbahagia';
        let currentGuestName = guestNameElement.textContent.trim();
        let lastScannedTimestamp = localStorage.getItem('lastScannedTimestamp') || '0';
        
        // Fungsi untuk memeriksa tamu baru
        function checkNewGuest() {
            fetch(`/welcome?check=1&_=${Date.now()}`)
                .then(response => response.text())
                .then(html => {
                    // Ekstrak nama tamu dari HTML yang dikembalikan
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;
                    const newGuestElement = tempDiv.querySelector('#guest-name');
                    
                    if (newGuestElement) {
                        const newGuestName = newGuestElement.textContent.trim();
                        
                        // Jika nama tamu baru berbeda dan bukan pesan default, reload halaman
                        if (newGuestName !== currentGuestName && 
                            newGuestName !== defaultMessage && 
                            !newGuestName.includes(currentGuestName)) {
                            
                            // Simpan timestamp terakhir untuk mencegah loop reload
                            const now = Date.now().toString();
                            localStorage.setItem('lastScannedTimestamp', now);
                            
                            // Reload halaman untuk menampilkan tamu baru
                            window.location.reload();
                        }
                    }
                })
                .catch(error => console.error('Error checking for new guest:', error));
        }
        
        // Periksa tamu baru setiap 2 detik
        const checkInterval = setInterval(checkNewGuest, 5000);
        
        // Jika halaman menampilkan tamu (bukan pesan default), atur timer untuk kembali ke default
        if (currentGuestName !== defaultMessage && !currentGuestName.includes(defaultMessage)) {
            setTimeout(() => {
                guestNameElement.classList.remove('animate-fade-in');
                void guestNameElement.offsetWidth; // Trigger reflow
                guestNameElement.textContent = defaultMessage;
                guestNameElement.classList.add('animate-fade-in');
                
                // Hapus dari cache server (jika route tersedia)
                try {
                    fetch('/clear-last-guest', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    }).catch(e => console.log('Error clearing cache'));
                } catch (e) {
                    console.log('Error trying to clear cache');
                }
                
                // Force reload setelah membersihkan cache
                setTimeout(() => {
                    window.location.reload();
                }, 5000);
            }, 5000);
        }
    });
</script>
<style>
    .animate-fade-in {
        animation: fadeIn 1s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection