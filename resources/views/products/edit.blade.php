@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">&larr; Kembali ke Katalog</a>
</div>

<div class="form-container">
    <h2>Edit Data Produk</h2>
    <p class="subtitle" style="margin-bottom: 20px;">Perbarui data inventaris produk (stok dikelola melalui mutasi stok).</p>

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="sku">Kode SKU *</label>
            <input type="text" id="sku" name="sku" required value="{{ old('sku', $product->sku) }}">
            @error('sku') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="name">Nama Produk *</label>
            <input type="text" id="name" name="name" required value="{{ old('name', $product->name) }}">
            @error('name') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="category_id">Kategori *</label>
            <select id="category_id" name="category_id" required>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <div class="form-group" style="flex: 1; min-width: 150px;">
                <label for="price">Harga Satuan (Rp) *</label>
                <input type="number" id="price" name="price" step="1000" min="0" required value="{{ old('price', (int)$product->price) }}">
                @error('price') <small style="color: #f87171;">{{ $message }}</small> @enderror
            </div>

            <div class="form-group" style="flex: 1; min-width: 120px;">
                <label for="stock_static">Stok Saat Ini (Read-only)</label>
                <input type="text" id="stock_static" value="{{ $product->stock }} pcs" disabled style="background-color: #334155; opacity: 0.8;">
                <small>Ubah kuantitas melalui menu Mutasi Stok.</small>
            </div>

            <div class="form-group" style="flex: 1; min-width: 120px;">
                <label for="minimum_stock">Batas Minimum Stok *</label>
                <input type="number" id="minimum_stock" name="minimum_stock" min="0" required value="{{ old('minimum_stock', $product->minimum_stock) }}">
                @error('minimum_stock') <small style="color: #f87171;">{{ $message }}</small> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Pilih Supplier Terkait (Many-to-Many):</label>
            <div style="background-color: #0f172a; border: 1px solid #334155; border-radius: 6px; padding: 12px; max-height: 140px; overflow-y: auto;">
                @foreach ($suppliers as $s)
                    <label style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px; cursor: pointer; color: #f8fafc; font-weight: normal;">
                        <input type="checkbox" name="suppliers[]" value="{{ $s->id }}" 
                            {{ in_array($s->id, old('suppliers', $selectedSuppliers)) ? 'checked' : '' }}>
                        {{ $s->name }}
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-warning" style="width: 100%; margin-top: 10px;">Simpan Perubahan</button>
    </form>
</div>
@endsection
