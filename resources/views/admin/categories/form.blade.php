@extends('layouts.admin')

@section('title', $category->exists ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')
    <a href="{{ route('admin.categories.index') }}" class="small">Kembali ke kategori</a>
    <h1 class="mb-3">{{ $category->exists ? 'Ubah kategori' : 'Tambah kategori' }}</h1>
    <form method="POST" action="{{ $category->exists ? route('admin.categories.update', $category) : route('admin.categories.store') }}" class="card card-body kk-form" novalidate>
        @csrf
        @if ($category->exists) @method('PUT') @endif
        <x-field name="nama" label="Nama" :value="$category->nama" required maxlength="50" />
        <x-field name="urutan" label="Urutan tampil" type="number" :value="$category->urutan" required min="0" max="1000" help="Angka kecil tampil lebih dulu di katalog." />
        <div><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
@endsection
