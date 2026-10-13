@extends('layouts.app')

@section('title', 'Master Kategori')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px;">
    <div>
        <h1>Master Data Kategori</h1>
        <p class="subtitle">Kelola kelompok kategori inventaris produk.</p>
    </div>
    <a href="{{ route('categories.create') }}" class="btn btn-primary">+ Tambah Kategori Baru</a>
</div>

<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah Produk Terkait</th>
                <th style="text-align: right;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $cat)
                <tr>
                    <td>#{{ $cat->id }}</td>
                    <td><strong>{{ $cat->name }}</strong></td>
                    <td>{{ $cat->description ?: '-' }}</td>
                    <td><span class="badge badge-info">{{ $cat->products_count }} Produk</span></td>
                    <td style="text-align: right; white-space: nowrap;">
                        <a href="{{ route('categories.edit', $cat) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form method="POST" action="{{ route('categories.destroy', $cat) }}" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: #94a3b8; padding: 25px;">Belum ada data kategori.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
