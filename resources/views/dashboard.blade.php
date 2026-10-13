@extends('layouts.app')

@section('title', 'Dashboard Ringkasan Inventory')

@section('content')
<header>
    <h1>Dashboard Ringkasan Inventory</h1>
    <p class="subtitle">Pantau kondisi stok kamera retro, nilai persediaan, dan log transaksi secara real-time melalui Laravel Eloquent.</p>
</header>

<!-- Widget Statistik Sesuai Slide 29 Modul P4 & P5 -->
<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Produk</div>
        <div class="stat-value">{{ number_format($totalProducts) }}</div>
        <div class="stat-desc">Item SKU terdaftar</div>
    </div>

    <div class="stat-card stat-success">
        <div class="stat-label">Nilai Inventory</div>
        <div class="stat-value">Rp {{ number_format($totalInventoryValue, 0, ',', '.') }}</div>
        <div class="stat-desc">Estimasi total aset stok</div>
    </div>

    <div class="stat-card {{ $lowStockCount > 0 ? 'stat-danger' : 'stat-success' }}">
        <div class="stat-label">Produk Stok Rendah</div>
        <div class="stat-value">{{ number_format($lowStockCount) }}</div>
        <div class="stat-desc">Stok &le; batas minimum</div>
    </div>

    <div class="stat-card stat-warning">
        <div class="stat-label">Total Masuk / Keluar</div>
        <div class="stat-value" style="font-size: 1.35rem;">
            <span style="color: #4ade80;">+{{ number_format($totalIn) }}</span> / 
            <span style="color: #f87171;">-{{ number_format($totalOut) }}</span>
        </div>
        <div class="stat-desc">Akumulasi pergerakan unit</div>
    </div>
</div>

<!-- Quick Actions -->
<div class="top-bar">
    <div style="font-weight: 600; color: #e2e8f0;">⚡ Operasional Cepat:</div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
        <a href="{{ route('stock.create', ['type' => 'IN']) }}" class="btn btn-success">+ Catat Barang Masuk</a>
        <a href="{{ route('stock.create', ['type' => 'OUT']) }}" class="btn btn-danger">- Catat Barang Keluar</a>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn btn-primary">+ Tambah Produk Baru</a>
        @endif
    </div>
</div>

<!-- Peringatan Stok Menipis -->
@if($lowStockProducts->isNotEmpty())
<section style="margin-bottom: 30px;">
    <h2>⚠️ Peringatan Stok Menipis (Restock Diperlukan)</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Stok Sekarang</th>
                    <th>Batas Min.</th>
                    <th>Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lowStockProducts as $lp)
                    <tr>
                        <td><code>{{ $lp->sku }}</code></td>
                        <td><strong>{{ $lp->name }}</strong></td>
                        <td><span class="badge badge-info">{{ $lp->category->name }}</span></td>
                        <td><span class="badge badge-danger">{{ $lp->stock }} Unit</span></td>
                        <td>{{ $lp->minimum_stock }} Unit</td>
                        <td>
                            <a href="{{ route('stock.create', ['type' => 'IN', 'product_id' => $lp->id]) }}" class="btn btn-sm btn-success">+ Restock Masuk</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endif

<!-- Laporan Kategori Berbasis Aggregate Function (Slide 14 & 28) -->
<section style="margin-bottom: 30px;">
    <h2>📊 Ringkasan Laporan per Kategori (Eloquent Agregasi)</h2>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Jumlah Jenis Produk</th>
                    <th>Total Kuantitas Stok</th>
                    <th>Rata-rata Harga Satuan</th>
                    <th>Total Nilai Valuasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categoryReports as $cr)
                    <tr>
                        <td><strong>{{ $cr->name }}</strong></td>
                        <td>{{ number_format($cr->products_count) }} SKU</td>
                        <td>{{ number_format($cr->total_stock) }} pcs</td>
                        <td>Rp {{ number_format($cr->avg_price, 0, ',', '.') }}</td>
                        <td><strong>Rp {{ number_format($cr->category_value, 0, ',', '.') }}</strong></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<!-- 5 Riwayat Pergerakan Stok Terkini -->
<section>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h2>🕒 Transaksi Pergerakan Terkini</h2>
        <a href="{{ route('stock.index') }}" class="btn btn-sm btn-secondary">Lihat Semua Riwayat &rarr;</a>
    </div>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Tipe</th>
                    <th>SKU & Produk</th>
                    <th>Kuantitas</th>
                    <th>Oleh User</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentMovements as $rm)
                    <tr>
                        <td>{{ $rm->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if ($rm->type === 'IN')
                                <span class="badge badge-success">MASUK (IN)</span>
                            @else
                                <span class="badge badge-danger">KELUAR (OUT)</span>
                            @endif
                        </td>
                        <td><strong>{{ $rm->product->name }}</strong> <small>({{ $rm->product->sku }})</small></td>
                        <td><strong>{{ ($rm->type === 'IN' ? '+' : '-') . $rm->quantity }}</strong></td>
                        <td>👤 {{ $rm->user->name }}</td>
                        <td>{{ $rm->notes ?: '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: #94a3b8;">Belum ada riwayat transaksi barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
@endsection
