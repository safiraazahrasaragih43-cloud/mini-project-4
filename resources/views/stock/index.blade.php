@extends('layouts.app')

@section('title', 'Riwayat Pergerakan Stok')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1>Histori Mutasi & Pergerakan Stok</h1>
        <p class="subtitle">Log audit setiap barang masuk (IN) dan barang keluar (OUT) beserta pencatatnya.</p>
    </div>
    <div style="display: flex; gap: 10px;">
        <a href="{{ route('stock.create', ['type' => 'IN']) }}" class="btn btn-success">+ Transaksi Masuk (IN)</a>
        <a href="{{ route('stock.create', ['type' => 'OUT']) }}" class="btn btn-danger">- Transaksi Keluar (OUT)</a>
    </div>
</div>

<!-- Filter Bar -->
<form method="GET" action="{{ route('stock.index') }}" class="top-bar">
    <div class="filter-bar">
        <div style="flex: 2; min-width: 220px;">
            <select name="product_id">
                <option value="">Semua Produk Kamera</option>
                @foreach ($products as $p)
                    <option value="{{ $p->id }}" {{ request('product_id') == $p->id ? 'selected' : '' }}>
                        {{ $p->name }} ({{ $p->sku }})
                    </option>
                @endforeach
            </select>
        </div>

        <div style="flex: 1; min-width: 140px;">
            <select name="type">
                <option value="">Semua Tipe Transaksi</option>
                <option value="IN" {{ request('type') === 'IN' ? 'selected' : '' }}>Barang Masuk (IN)</option>
                <option value="OUT" {{ request('type') === 'OUT' ? 'selected' : '' }}>Barang Keluar (OUT)</option>
            </select>
        </div>

        <div>
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('stock.index') }}" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<!-- Table -->
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Waktu Transaksi</th>
                <th>Tipe</th>
                <th>SKU</th>
                <th>Nama Produk</th>
                <th>Kuantitas</th>
                <th>Dicatat Oleh</th>
                <th>Catatan / Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($movements as $m)
                <tr>
                    <td>{{ $m->created_at->format('d M Y, H:i:s') }}</td>
                    <td>
                        @if ($m->type === 'IN')
                            <span class="badge badge-success">MASUK (IN)</span>
                        @else
                            <span class="badge badge-danger">KELUAR (OUT)</span>
                        @endif
                    </td>
                    <td><code>{{ $m->product->sku }}</code></td>
                    <td><strong>{{ $m->product->name }}</strong></td>
                    <td style="font-weight: bold; font-size: 1.05rem; color: {{ $m->type === 'IN' ? '#4ade80' : '#f87171' }};">
                        {{ ($m->type === 'IN' ? '+' : '-') . $m->quantity }} pcs
                    </td>
                    <td>👤 {{ $m->user->name }}</td>
                    <td>{{ $m->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px; color: #94a3b8;">
                        Tidak ada riwayat pergerakan stok yang cocok dengan kriteria.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 20px;">
    {{ $movements->links() }}
</div>
@endsection
