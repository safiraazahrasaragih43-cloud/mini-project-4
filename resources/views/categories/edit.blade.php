@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">&larr; Kembali ke Master Kategori</a>
</div>

<div class="form-container">
    <h2>Edit Data Kategori</h2>
    <p class="subtitle" style="margin-bottom: 20px;">Ubah rincian kategori produk.</p>

    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Nama Kategori *</label>
            <input type="text" id="name" name="name" required value="{{ old('name', $category->name) }}">
            @error('name') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="description">Deskripsi</label>
            <textarea id="description" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
            @error('description') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-warning" style="width: 100%; margin-top: 10px;">Simpan Perubahan</button>
    </form>
</div>
@endsection
