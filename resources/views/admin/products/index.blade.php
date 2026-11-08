@extends('layouts.admin')

@section('title', 'Produk')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="mb-0">Produk</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Tambah produk</a>
    </div>

    <form method="GET" class="row g-2 align-items-end mb-3" role="search">
        <x-field name="q" label="Nama produk" :value="$filter['q'] ?? ''" class="col-sm-6 col-lg-4 mb-0" />
        <x-field name="kategori" label="Kategori" type="select" :value="$filter['kategori'] ?? ''" class="col-sm-4 col-lg-3 mb-0"
            :options="['' => 'Semua kategori'] + $categories->pluck('nama', 'id')->all()" />
        <div class="col-auto"><button class="btn btn-primary" type="submit">Cari</button></div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead><tr><th scope="col"><span class="visually-hidden">Gambar</span></th><th scope="col">Nama</th><th scope="col">Kategori</th><th scope="col" class="text-end">Harga dasar</th><th scope="col">Status</th><th scope="col"><span class="visually-hidden">Aksi</span></th></tr></thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td><img src="{{ $product->gambar_url }}" alt="" width="56" height="42" class="rounded object-fit-cover" loading="lazy"></td>
                        <td>{{ $product->nama }}<div class="small text-body-secondary">{{ $product->option_groups_count }} grup opsi</div></td>
                        <td>{{ $product->category->nama }}</td>
                        <td class="text-end text-nowrap">@rp($product->harga_dasar)</td>
                        <td><span class="badge {{ $product->tersedia ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $product->tersedia ? 'Tersedia' : 'Habis' }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                            <a href="{{ route('admin.products.options', $product) }}" class="btn btn-sm btn-outline-primary">Opsi</a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-delete-url="{{ route('admin.products.destroy', $product) }}" data-delete-name="{{ $product->nama }}">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-body-secondary">Tidak ada produk yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
@endsection
