@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">&larr; Kembali ke Master Kategori</a>
</div>

<div class="form-container">
    <h2>Tambah Kategori Baru</h2>
    <p class="subtitle" style="margin-bottom: 20px;">Tambahkan grup kategori produk baru.</p>

    <form method="POST" action="{{ route('categories.store') }}">
        @csrf

        <div class="form-group">
            <label for="name">Nama Kategori *</label>
            <input type="text" id="name" name="name" required placeholder="Contoh: Kamera Medium Format" value="{{ old('name') }}">
            @error('name') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="3" placeholder="Deskripsi jenis produk dalam kategori ini">{{ old('description') }}</textarea>
            @error('description') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Simpan Kategori</button>
    </form>
</div>
@endsection
