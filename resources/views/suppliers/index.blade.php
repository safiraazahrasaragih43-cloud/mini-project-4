@extends('layouts.app')

@section('title', 'Master Supplier')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1>Master Data Supplier (Vendor)</h1>
        <p class="subtitle">Kelola daftar rekanan vendor penyedia stok produk (Many-to-Many via Eloquent).</p>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">+ Tambah Supplier Baru</a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Vendor / Supplier</th>
                <th>Kontak Person</th>
                <th>No. Telepon</th>
                <th>Alamat</th>
                <th>Produk Disuplai</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($suppliers as $s)
                <tr>
                    <td>#{{ $s->id }}</td>
                    <td><strong>{{ $s->name }}</strong></td>
                    <td>{{ $s->contact_person ?: '-' }}</td>
                    <td>{{ $s->phone ?: '-' }}</td>
                    <td>{{ $s->address ?: '-' }}</td>
                    <td><span class="badge badge-info">{{ $s->products_count }} Item</span></td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="{{ route('suppliers.edit', $s) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('suppliers.destroy', $s) }}" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94a3b8; padding: 25px;">Belum ada supplier terdaftar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
