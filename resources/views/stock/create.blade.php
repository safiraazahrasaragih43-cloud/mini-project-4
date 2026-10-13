@extends('layouts.app')

@section('title', 'Catat Mutasi Stok')

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('stock.index') }}" class="btn btn-secondary">&larr; Kembali ke Histori Mutasi</a>
</div>

<div class="form-container">
    <h2>Catat Transaksi Mutasi Stok</h2>
    <p class="subtitle" style="margin-bottom: 20px;">
        Pastikan input valid. Transaksi berjalan secara atomik (ACID) menggunakan DB::transaction & lockForUpdate.
    </p>

    <form method="POST" action="{{ route('stock.store') }}">
        @csrf

        <div class="form-group">
            <label for="type">Jenis Mutasi *</label>
            <select id="type" name="type" required>
                <option value="IN" {{ old('type', $defaultType) === 'IN' ? 'selected' : '' }}>➕ BARANG MASUK (IN) - Menambah Stok</option>
                <option value="OUT" {{ old('type', $defaultType) === 'OUT' ? 'selected' : '' }}>➖ BARANG KELUAR (OUT) - Mengurangi Stok</option>
            </select>
            @error('type') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="product_id">Pilih Produk Kamera / Item *</label>
            <select id="product_id" name="product_id" required>
                <option value="">-- Pilih Produk --</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}" {{ old('product_id', $selectedProductId) == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} (SKU: {{ $p->sku }} | Stok: {{ $p->stock }} pcs)
                    </option>
                @endforeach
            </select>
            @error('product_id') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label for="quantity">Kuantitas / Jumlah Unit *</label>
            <input type="number" id="quantity" name="quantity" min="1" required placeholder="Contoh: 5" value="{{ old('quantity') }}">
            @error('quantity') <small style="color: #f87171;">{{ $message }}</small> @enderror
            <small>Kuantitas harus berupa bilangan bulat positif lebih besar dari nol.</small>
        </div>

        <div class="form-group">
            <label for="notes">Catatan Transaksi / Keterangan</label>
            <textarea id="notes" name="notes" rows="3" placeholder="Contoh: Suplai batch PO #102, atau Penjualan invoice #551">{{ old('notes') }}</textarea>
            @error('notes') <small style="color: #f87171;">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">
            Simpan & Eksekusi Mutasi Stok
        </button>
    </form>
</div>
@endsection
