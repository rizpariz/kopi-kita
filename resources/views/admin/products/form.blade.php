@extends('layouts.admin')

@section('title', $product->exists ? 'Ubah Produk' : 'Tambah Produk')

@section('content')
    <a href="{{ route('admin.products.index') }}" class="small">Kembali ke produk</a>
    <h1 class="mb-3">{{ $product->exists ? 'Ubah '.$product->nama : 'Tambah produk' }}</h1>
    <form method="POST" action="{{ $product->exists ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" class="card card-body kk-form" novalidate>
        @csrf
        @if ($product->exists) @method('PUT') @endif
        <x-field name="nama" label="Nama" :value="$product->nama" required maxlength="100" />
        <x-field name="category_id" label="Kategori" type="select" :value="$product->category_id" :options="$categories->pluck('nama', 'id')->all()" />
        <x-field name="deskripsi" label="Deskripsi" type="textarea" :value="$product->deskripsi" maxlength="1000" />
        <x-field name="harga_dasar" label="Harga dasar (Rp)" type="number" :value="$product->exists ? (int) $product->harga_dasar : null" required min="1000" step="500"
            :help="$product->exists ? 'Perubahan harga tercatat di log audit. Pesanan lama tetap memakai harga saat dipesan.' : null" />
        <div class="d-flex gap-3 align-items-start">
            <img src="{{ $product->gambar_url }}" alt="Pratinjau gambar" width="120" height="90" class="rounded border object-fit-cover" id="gambar-preview">
            <x-field name="gambar" label="Gambar" type="file" accept="image/jpeg,image/png,image/webp" class="flex-grow-1" help="JPG, PNG, atau WEBP, maksimal 2 MB." />
        </div>
        <x-field name="tersedia" label="Tersedia" type="switch" :value="$product->tersedia" />
        <div><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
@endsection

@push('scripts')
    <script>
        $('input[name=gambar]').on('change', function () {
            if (this.files[0]) {
                $('#gambar-preview').attr('src', URL.createObjectURL(this.files[0]));
            }
        });
    </script>
@endpush
