@extends('layouts.app')

@section('title', 'Tambah Supplier')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">&larr; Kembali ke Master Supplier</a>
</div>

<div class="form-container">
    <h2>Tambah Supplier Baru</h2>
    <p class="subtitle" style="margin-bottom: 20px;">Daftarkan rekanan distributor / vendor baru.</p>

    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Vendor / Perusahaan Supplier *</label>
            <input type="text" id="name" name="name" required placeholder="Contoh: CV Prima Optik Sentosa" value="{{ old('name') }}">
            @error('name') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="contact_person">Kontak Person (PIC)</label>
            <input type="text" id="contact_person" name="contact_person" placeholder="Contoh: Rian Gunawan" value="{{ old('contact_person') }}">
            @error('contact_person') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon / WhatsApp</label>
            <input type="text" id="phone" name="phone" placeholder="Contoh: 081298765432" value="{{ old('phone') }}">
            @error('phone') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="address">Alamat Kantor / Gudang</label>
            <textarea id="address" name="address" rows="3" placeholder="Alamat lengkap vendor...">{{ old('address') }}</textarea>
            @error('address') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Simpan Supplier</button>
    </form>
</div>
@endsection
