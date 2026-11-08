@extends('layouts.app')

@section('title', 'Menu')

@section('content')
    <div class="kk-menu-head d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-3">
        <div>
            <h1 class="mb-2">Menu</h1>
            <p class="text-body-secondary mb-0">Pilih menu, atur sesuai selera, lalu ambil di kedai.</p>
        </div>
        <form method="GET" action="{{ route('menu.index') }}" role="search" class="col-md-5 col-lg-4">
            @if ($kategori)
                <input type="hidden" name="kategori" value="{{ $kategori }}">
            @endif
            <label for="q" class="visually-hidden">Cari menu</label>
            <div class="input-group">
                <input type="search" id="q" name="q" value="{{ $q }}" maxlength="100" class="form-control" placeholder="Cari menu, misalnya latte">
                <button class="btn btn-primary" type="submit">Cari</button>
            </div>
        </form>
    </div>

    @if ($kkStore && ! $kkOpen)
        <div class="alert alert-warning" role="status">
            {{ $kkStore->pemesanan_dibuka ? 'Kedai sedang tutup.' : 'Pemesanan sedang ditutup sementara.' }}
            Kamu tetap bisa melihat menu dan mengisi keranjang. Pesanan diterima pukul {{ substr($kkStore->jam_buka, 0, 5) }} sampai {{ substr($kkStore->jam_tutup, 0, 5) }}.
        </div>
    @endif

    <nav class="kk-cat-nav py-2 mb-4" aria-label="Kategori menu">
        <ul class="nav nav-pills gap-1">
            <li class="nav-item">
                <a class="nav-link @if (! $kategori) active @endif" @if (! $kategori) aria-current="page" @endif
                    href="{{ route('menu.index', array_filter(['q' => $q])) }}">Semua</a>
            </li>
            @foreach ($categories as $category)
                <li class="nav-item">
                    <a class="nav-link @if ($kategori == $category->id) active @endif" @if ($kategori == $category->id) aria-current="page" @endif
                        href="{{ route('menu.index', array_filter(['kategori' => $category->id, 'q' => $q])) }}">{{ $category->nama }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    @if ($q !== '')
        <p class="text-body-secondary">Hasil pencarian untuk "{{ $q }}". <a href="{{ route('menu.index', array_filter(['kategori' => $kategori])) }}">Hapus pencarian</a></p>
    @endif

    @forelse ($sections as $category)
        <section class="kk-section mb-5" id="kategori-{{ $category->id }}" aria-labelledby="judul-kategori-{{ $category->id }}">
            <h2 class="kk-section-title mb-3" id="judul-kategori-{{ $category->id }}">
                {{ $category->nama }} <small>{{ $category->products->count() }} menu</small>
            </h2>
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
                @foreach ($category->products as $product)
                    <div class="col">
                        <article class="card kk-product h-100 @unless ($product->tersedia) is-habis @endunless">
                            <div class="position-relative">
                                <img src="{{ $product->gambar_url }}" alt="" class="card-img-top" width="400" height="300" loading="lazy" decoding="async">
                                @unless ($product->tersedia)
                                    <span class="badge text-bg-dark kk-habis-badge">Habis</span>
                                @endunless
                            </div>
                            <div class="card-body d-flex flex-column p-3">
                                <h3 class="card-title mb-1">{{ $product->nama }}</h3>
                                <p class="card-text small text-body-secondary mb-3">{{ $product->deskripsi }}</p>
                                <div class="mt-auto d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <span class="kk-price">Rp{{ number_format($product->harga_dasar, 0, ',', '.') }}</span>
                                    @if ($product->tersedia)
                                        <button type="button" class="btn btn-primary btn-sm px-3 js-customize" data-product-id="{{ $product->id }}"
                                            aria-label="Tambah {{ $product->nama }}">Tambah</button>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-sm px-3" disabled aria-label="{{ $product->nama }} habis">Habis</button>
                                    @endif
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </section>
    @empty
        <div class="text-center py-5">
            <h2 class="h4">Menu tidak ditemukan</h2>
            <p class="text-body-secondary">Coba kata kunci lain atau lihat semua kategori.</p>
            <a href="{{ route('menu.index') }}" class="btn btn-outline-primary">Lihat semua menu</a>
        </div>
    @endforelse

    @include('customer.partials.customize-modal')
@endsection

@push('scripts')
    <script src="{{ asset('js/menu.js') }}"></script>
@endpush
