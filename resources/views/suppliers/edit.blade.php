@extends('layouts.app')

@section('title', 'Edit Supplier')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">&larr; Kembali ke Master Supplier</a>
</div>

<div class="form-container">
    <h2>Edit Data Supplier</h2>
    <p class="subtitle" style="margin-bottom: 20px;">Perbarui profil dan kontak supplier.</p>

    <form method="POST" action="{{ route('suppliers.update', $supplier) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Vendor / Perusahaan Supplier *</label>
            <input type="text" id="name" name="name" required value="{{ old('name', $supplier->name) }}">
            @error('name') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="contact_person">Kontak Person (PIC)</label>
            <input type="text" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}">
            @error('contact_person') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon / WhatsApp</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
            @error('phone') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="address">Alamat Kantor / Gudang</label>
            <textarea id="address" name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
            @error('address') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-warning" style="width: 100%; margin-top: 10px;">Simpan Perubahan</button>
    </form>
</div>
@endsection
