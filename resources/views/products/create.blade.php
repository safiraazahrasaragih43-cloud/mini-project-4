@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">&larr; Kembali ke Katalog</a>
</div>

<div class="form-container">
    <h2>Tambah Produk Baru</h2>
    <p class="subtitle" style="margin-bottom: 20px;">Isi data detail produk dan hubungkan dengan supplier.</p>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf

        <div class="form-group">
            <label for="sku">Kode SKU (Unik) *</label>
            <input type="text" id="sku" name="sku" required placeholder="Contoh: CAM-RET-01" value="{{ old('sku') }}">
            @error('sku') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="name">Nama Produk *</label>
            <input type="text" id="name" name="name" required placeholder="Contoh: Olympus Trip 35 Black" value="{{ old('name') }}">
            @error('name') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="category_id">Kategori *</label>
            <select id="category_id" name="category_id" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 150px;">
                <label for="price">Harga Satuan (Rp) *</label>
                <input type="number" id="price" name="price" step="1000" min="0" required placeholder="1500000" value="{{ old('price') }}">
                @error('price') <small style="color: #f87171;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group" style="flex: 1; min-width: 120px;">
                <label for="stock">Stok Awal *</label>
                <input type="number" id="stock" name="stock" min="0" required value="{{ old('stock', 0) }}">
                @error('stock') <small style="color: #f87171;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group" style="flex: 1; min-width: 120px;">
                <label for="minimum_stock">Batas Minimum Stok *</label>
                <input type="number" id="minimum_stock" name="minimum_stock" min="0" required value="{{ old('minimum_stock', 5) }}">
                @error('minimum_stock') <small style="color: #f87171;">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Pilih Supplier Terkait (Many-to-Many):</label>
            <div style="background-color: #0f172a; border: 1px solid #334155; border-radius: 6px; padding: 12px; max-height: 140px; overflow-y: auto;">
                @forelse ($suppliers as $s)
                    <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; color: #f8fafc; font-weight: normal;">
                        <input type="checkbox" name="suppliers[]" value="{{ $s->id }}" 
                            {{ in_array($s->id, old('suppliers', [])) ? 'checked' : '' }}>
                        {{ $s->name }}
                    </label>
                @empty
                    <small style="color: #94a3b8;">Belum ada data supplier terdaftar.</small>
                @endforelse
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Simpan Produk</button>
    </form>
</div>
@endsection
