@extends('layouts.guest')

@section('title', 'Tambah Tamu')

@section('content')
<div class="flex items-center justify-center min-h-screen">
    <div class="font-sans text-center m-5">
        <p class="text-lg">{{ $guest->name }}</p>
        <div class="qr-code my-5">
            {!! QrCode::size(200)->generate(route('guests.updateAttendance', $guest->slug)) !!}
        </div>
        <p class="font-bold">Tukarkan Voucher Untuk <br>Mengambil Souvenir</p>
        <button class="print-button mt-5 px-5 py-2 bg-green-500 text-white border-none cursor-pointer text-lg hover:bg-green-600" onclick="window.print()">Print</button>
    </div>
</div>
@endsection
