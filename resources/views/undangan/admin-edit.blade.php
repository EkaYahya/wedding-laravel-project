@php
use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('content')
{{-- Pastikan Anda sudah punya Bootstrap 5 & Bootstrap Icons di layout --}}
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <!-- Card -->
      <div class="card shadow" style="border-radius: 8px;">
        <div class="card-body p-4"  style = "border-top: 60px solid white;border-bottom: 60px solid white;">
          <!-- Judul -->
          <h4 class="text-center mb-4">Edit Detail Undangan</h4>

          <!-- Form -->
          <form action="{{ route('undangan.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nama Title -->
            <div class="mb-3">
              <label for="nama_title" class="form-label fw-semibold">Nama Title</label>
              <input 
                type="text"
                class="form-control"
                id="nama_title"
                name="nama_title"
                value="{{ $undangan->nama_title }}"
                placeholder="Misal: Jack And Rose Wedding Celebration"
              >
            </div>

            <!-- Video -->
            <div class="mb-3">
              <label for="video" class="form-label fw-semibold">Video</label>
              <input 
                type="url"
                class="form-control"
                id="video"
                name="video"
                value="{{ $undangan->video }}"
                placeholder="https://www.youtube.com/..."
              >
            </div>

            <!-- Nama Pasangan -->
            <div class="mb-3">
              <label for="nama_pasangan" class="form-label fw-semibold">Nama Pasangan</label>
              <input 
                type="text"
                class="form-control"
                id="nama_pasangan"
                name="nama_pasangan"
                value="{{ $undangan->nama_pasangan }}"
                placeholder="Misal: Jack &amp; Rose"
              >
            </div>

            <!-- Nama Laki-laki -->
            <div class="mb-3">
              <label for="nama_laki2" class="form-label fw-semibold">Nama Laki-laki</label>
              <input 
                type="text"
                class="form-control"
                id="nama_laki2"
                name="nama_laki2"
                value="{{ $undangan->nama_laki2 }}"
                placeholder="Misal: Jack Dawson"
              >
            </div>

            <!-- Keterangan Laki-laki -->
            <div class="mb-3">
              <label for="keterangan_laki2" class="form-label fw-semibold">Keterangan Laki-laki</label>
              <textarea
                class="form-control"
                id="keterangan_laki2"
                name="keterangan_laki2"
                rows="3"
                placeholder="Putra pertama dari keluarga Dawson..."
              >{{ $undangan->keterangan_laki2 }}</textarea>
            </div>

            <!-- Nama Perempuan -->
            <div class="mb-3">
              <label for="nama_prmp" class="form-label fw-semibold">Nama Perempuan</label>
              <input 
                type="text"
                class="form-control"
                id="nama_prmp"
                name="nama_prmp"
                value="{{ $undangan->nama_prmp }}"
                placeholder="Misal: Rose DeWitt Bukater"
              >
            </div>

            <!-- Keterangan Perempuan -->
            <div class="mb-3">
              <label for="keterengan_prpmp" class="form-label fw-semibold">Keterangan Perempuan</label>
              <textarea
                class="form-control"
                id="keterengan_prpmp"
                name="keterengan_prpmp"
                rows="3"
                placeholder="Putri pertama dari keluarga DeWitt Bukater..."
              >{{ $undangan->keterengan_prpmp }}</textarea>
            </div>

            <!-- Pembatas -->
            <hr class="my-4" />

            <!-- Nama Resepsi -->
            <div class="mb-3">
              <label for="nama_resepsi" class="form-label fw-semibold">Nama Resepsi</label>
              <input 
                type="text"
                class="form-control"
                id="nama_resepsi"
                name="nama_resepsi"
                value="{{ $undangan->nama_resepsi }}"
                placeholder="Misal: Resepsi Pernikahan Jack &amp; Rose"
              >
            </div>

            <!-- Keterangan Resepsi -->
            <div class="mb-3">
              <label for="keterangan_resepsi" class="form-label fw-semibold">Keterangan Resepsi</label>
              <textarea
                class="form-control"
                id="keterangan_resepsi"
                name="keterangan_resepsi"
                rows="3"
                placeholder="Acara resepsi akan diselenggarakan di..."
              >{{ $undangan->keterangan_resepsi }}</textarea>
            </div>

            <!-- Tempat Resepsi -->
            <div class="mb-3">
              <label for="tempat_resepsi" class="form-label fw-semibold">Tempat Resepsi</label>
              <input 
                type="text"
                class="form-control"
                id="tempat_resepsi"
                name="tempat_resepsi"
                value="{{ $undangan->tempat_resepsi }}"
                placeholder="Misal: Grand Ballroom Hotel ..."
              >
            </div>

            <!-- Jam Resepsi (pakai input-group + ikon) -->
            <div class="mb-3">
              <label for="jam_resepsi" class="form-label fw-semibold">Jam Resepsi</label>
              <div class="input-group">
                <input 
                  type="time"
                  class="form-control"
                  id="jam_resepsi"
                  name="jam_resepsi"
                  value="{{ $undangan->jam_resepsi }}"
                >
                <span class="input-group-text">
                  <i class="bi bi-clock"></i> {{-- Ikon dari Bootstrap Icons --}}
                </span>
              </div>
            </div>

            <!-- Tanggal Resepsi (pakai input-group + ikon) -->
            <div class="mb-4">
              <label for="tanggal_resepsi" class="form-label fw-semibold">Tanggal Resepsi</label>
              <div class="input-group">
                <input 
                  type="date"
                  class="form-control"
                  id="tanggal_resepsi"
                  name="tanggal_resepsi"
                  value="{{ $undangan->tanggal_resepsi }}"
                >
                <span class="input-group-text">
                  <i class="bi bi-calendar-date"></i> {{-- Ikon dari Bootstrap Icons --}}
                </span>
              </div>
            </div>

            <!-- Pembatas untuk Bagian Foto -->
            <hr class="my-4" />
            <h5 class="mb-4">Upload Foto</h5>

            <!-- Foto Pria -->
<div class="mb-3">
    <label for="foto_pria" class="form-label fw-semibold">Foto Pria</label>
    <input 
        type="file"
        class="form-control"
        id="foto_pria"
        name="foto_pria"
        accept="image/*"
    >
    @if($undangan->foto_pria)
        <div class="mt-2">
            <img src="{{ Storage::url($undangan->foto_pria) }}" alt="Foto Pria" class="img-thumbnail" style="height: 100px">
        </div>
    @endif
</div>

<!-- Foto Perempuan -->
<div class="mb-3">
    <label for="foto_prmp" class="form-label fw-semibold">Foto Perempuan</label>
    <input 
        type="file"
        class="form-control"
        id="foto_prmp"
        name="foto_prmp"
        accept="image/*"
    >
    @if($undangan->foto_prmp)
        <div class="mt-2">
            <img src="{{ Storage::url($undangan->foto_prmp) }}" alt="Foto Perempuan" class="img-thumbnail" style="height: 100px">
        </div>
    @endif
</div>

<!-- Foto Akad -->
<div class="mb-3">
    <label for="foto_akad" class="form-label fw-semibold">Foto Akad</label>
    <input 
        type="file"
        class="form-control"
        id="foto_akad"
        name="foto_akad"
        accept="image/*"
    >
    @if($undangan->foto_akad)
        <div class="mt-2">
            <img src="{{ Storage::url($undangan->foto_akad) }}" alt="Foto Akad" class="img-thumbnail" style="height: 100px">
        </div>
    @endif
</div>

<!-- Foto Resepsi -->
<div class="mb-4">
    <label for="foto_resepsi" class="form-label fw-semibold">Foto Resepsi</label>
    <input 
        type="file"
        class="form-control"
        id="foto_resepsi"
        name="foto_resepsi"
        accept="image/*"
    >
    @if($undangan->foto_resepsi)
        <div class="mt-2">
            <img src="{{ Storage::url($undangan->foto_resepsi) }}" alt="Foto Resepsi" class="img-thumbnail" style="height: 100px">
        </div>
    @endif
</div>

            <!-- Tombol Submit -->
            <div class="text-center">
              <button type="submit" class="btn btn-primary px-4 py-2">
                Update
              </button>
            </div>
          </form>
          <!-- End Form -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection