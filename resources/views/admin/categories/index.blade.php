@extends('layouts.admin')

@section('title', 'Kategori')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="mb-0">Kategori</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Tambah kategori</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead><tr><th scope="col">Urutan</th><th scope="col">Nama</th><th scope="col">Jumlah produk</th><th scope="col"><span class="visually-hidden">Aksi</span></th></tr></thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->urutan }}</td>
                        <td>{{ $category->nama }}</td>
                        <td><a href="{{ route('admin.products.index', ['kategori' => $category->id]) }}">{{ $category->products_count }} produk</a></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-delete-url="{{ route('admin.categories.destroy', $category) }}" data-delete-name="kategori {{ $category->nama }}">Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
