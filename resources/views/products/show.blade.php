@extends('layouts.app')

@section('title', 'Detail Produk - ' . $product->name)

@section('content')
<div style="margin-bottom: 20px;">
    <a href="{{ route('products.index') }}" class="btn btn-secondary">&larr; Kembali ke Katalog</a>
</div>

<div class="card" style="margin-bottom: 25px;">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 15px;">
        <div>
            <h1>{{ $product->name }}</h1>
            <p class="subtitle">SKU: <code>{{ $product->sku }}</code> • Kategori: <span class="badge badge-info">{{ $product->category->name }}</span></p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('stock.create', ['product_id' => $product->id]) }}" class="btn btn-success">+ Mutasi Stok</a>
            @if(Auth::user()->isAdmin())
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit Data</a>
            @endif
        </div>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-top: 20px;">
        <div style="background-color: #0f172a; padding: 15px; border-radius: 8px;">
            <small style="color: #94a3b8;">Harga Satuan</small>
            <div style="font-size: 1.3rem; font-weight: bold; color: #4ade80;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        </div>
        <div style="background-color: #0f172a; padding: 15px; border-radius: 8px;">
            <small style="color: #94a3b8;">Stok Tersedia</small>
            <div style="font-size: 1.3rem; font-weight: bold; color: {{ $product->isLowStock() ? '#ef4444' : '#f8fafc' }};">
                {{ $product->stock }} unit
                @if($product->isLowStock()) <span class="badge badge-danger">Menipis &le; {{ $product->minimum_stock }}</span> @endif
            </div>
        </div>
        <div style="background-color: #0f172a; padding: 15px; border-radius: 8px;">
            <small style="color: #94a3b8;">Batas Minimum Stok</small>
            <div style="font-size: 1.3rem; font-weight: bold; color: #cbd5e1;">{{ $product->minimum_stock }} unit</div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <h3>Rekanan Vendor / Supplier:</h3>
        <p style="margin-top: 6px; color: #cbd5e1;">
            {{ $product->suppliers->pluck('name')->join(' • ') ?: 'Belum ada supplier yang ditautkan.' }}
        </p>
    </div>
</div>

<div class="table-responsive">
    <div style="padding: 15px; font-weight: 600; border-bottom: 1px solid #334155;">
        Riwayat Mutasi Stok Produk Ini ({{ $product->stockMovements->count() }} Transaksi)
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>Waktu</th>
                <th>Tipe</th>
                <th>Kuantitas</th>
                <th>Dicatat Oleh</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($product->stockMovements as $sm)
                <tr>
                    <td>{{ $sm->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        @if($sm->type === 'IN')
                            <span class="badge badge-success">MASUK (IN)</span>
                        @else
                            <span class="badge badge-danger">KELUAR (OUT)</span>
                        @endif
                    </td>
                    <td><strong>{{ ($sm->type === 'IN' ? '+' : '-') . $sm->quantity }} pcs</strong></td>
                    <td>👤 {{ $sm->user->name }}</td>
                    <td>{{ $sm->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 20px;">Belum ada riwayat pergerakan stok untuk produk ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
