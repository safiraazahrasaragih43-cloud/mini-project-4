@extends('layouts.app')

@section('title', 'Katalog Produk')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1>Katalog & Inventaris Produk</h1>
        <p class="subtitle">Kelola master data produk, status stok, relasi kategori dan supplier melalui Laravel Eloquent.</p>
    </div>
    @if(Auth::user()->isAdmin())
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk Baru</a>
    @endif
</div>

<!-- Search, Filter & Sort Form -->
<form method="GET" action="{{ route('products.index') }}" class="top-bar">
    <div class="filter-bar">
        <div style="flex: 2; min-width: 220px;">
            <input type="text" name="search" placeholder="Cari nama kamera / SKU..." value="{{ request('search') }}">
        </div>
        
        <div style="flex: 1.5; min-width: 180px;">
            <select name="category_id">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1.2; min-width: 150px;">
            <select name="sort">
                <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>Urut: Nama</option>
                <option value="sku" {{ $sortBy === 'sku' ? 'selected' : '' }}>Urut: SKU</option>
                <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Urut: Harga</option>
                <option value="stock" {{ $sortBy === 'stock' ? 'selected' : '' }}>Urut: Stok</option>
                <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Urut: Terbaru</option>
            </select>
        </div>

        <div style="flex: 1; min-width: 110px;">
            <select name="dir">
                <option value="ASC" {{ $orderDir === 'asc' ? 'selected' : '' }}>A-Z / Naik</option>
                <option value="DESC" {{ $orderDir === 'desc' ? 'selected' : '' }}>Z-A / Turun</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<!-- Products Table -->
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga Satuan</th>
                <th>Stok</th>
                <th>Supplier Terhubung</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $p)
                <tr>
                    <td><code>{{ $p->sku }}</code></td>
                    <td>
                        <a href="{{ route('products.show', $p) }}" style="color: #f1f5f9; text-decoration: none; font-weight: bold;">
                            {{ $p->name }}
                        </a>
                    </td>
                    <td><span class="badge badge-info">{{ $p->category->name }}</span></td>
                    <td style="color: #4ade80; font-weight: 600;">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                    <td>
                        @if ($p->isLowStock())
                            <span class="badge badge-danger">{{ $p->stock }} pcs (Rendah &le; {{ $p->minimum_stock }})</span>
                        @else
                            <span class="badge badge-success">{{ $p->stock }} pcs</span>
                        @endif
                    </td>
                    <td style="font-size: 0.85rem; color: #cbd5e1; max-width: 200px;">
                        {{ $p->suppliers->pluck('name')->join(', ') ?: 'Belum ada supplier' }}
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        <div style="display: inline-flex; gap: 6px;">
                            <a href="{{ route('stock.create', ['product_id' => $p->id]) }}" class="btn btn-sm btn-success" title="Mutasi Stok">Mutasi</a>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('products.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ route('products.destroy', $p) }}" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Tidak ada produk yang sesuai dengan kriteria pencarian.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Pagination Links retaining query string -->
<div style="margin-top: 20px;">
    {{ $products->links() }}
</div>
@endsection
