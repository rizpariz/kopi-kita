@extends('layouts.app')

@section('title', 'Beranda')

@php
    $customer = auth()->user()?->hasRole(\App\Enums\Role::Pelanggan);
@endphp

@section('content')
    {{-- ===== Sampul (hero) ===== --}}
    <section class="kk-hero">
        {{-- Baris kepala majalah --}}
        <div class="kk-hero-meta">
            <span class="kk-mono">Edisi No. 01 &mdash; Kopi untuk dibawa pulang</span>
            @if ($kkStore)
                <p class="kk-open-state {{ $kkOpen ? 'is-open' : 'is-closed' }}">
                    {{ $kkOpen ? 'Buka sekarang' : 'Sedang tutup' }}, pesanan diterima {{ substr($kkStore->jam_buka, 0, 5) }} sampai {{ substr($kkStore->jam_tutup, 0, 5) }}
                </p>
            @endif
        </div>

        {{-- Judul raksasa yang menabrak gambar sampul --}}
        <div class="kk-hero-stage">
            <h1 class="kk-hero-title">
                <span>Ngopi</span>
                <span class="kk-indent">tanpa</span>
                <span>antre.</span>
            </h1>

            <figure class="kk-cover mb-0" aria-hidden="true">
                <span class="kk-cover-tag">
                    Kode pesanan
                    <strong>K7QF3M</strong>
                    <span class="kk-live">Siap diambil</span>
                </span>
                <div class="kk-glass">
                    <span class="kk-glass-straw"></span>
                    <span class="kk-glass-cup">
                        <span class="kk-ice kk-ice-1"></span>
                        <span class="kk-ice kk-ice-2"></span>
                        <span class="kk-ice kk-ice-3"></span>
                    </span>
                </div>
                <figcaption class="kk-cover-caption">
                    <span>Gbr. 01</span>
                    <span>Es aren latte</span>
                </figcaption>
                <span class="kk-badge-spin">
                    <svg viewBox="0 0 120 120">
                        <defs>
                            <path id="kk-circle-path" d="M60,60 m-46,0 a46,46 0 1,1 92,0 a46,46 0 1,1 -92,0"/>
                        </defs>
                        <text><textPath href="#kk-circle-path" textLength="286">PESAN DULU &#183; AMBIL TANPA ANTRE &#183; </textPath></text>
                    </svg>
                    <span class="kk-badge-spin-center">k&amp;k</span>
                </span>
            </figure>
        </div>

        {{-- Baris bawah: aksen "literally." + teks + tombol --}}
        <div class="kk-hero-foot">
            <p class="kk-hero-accent" aria-hidden="true">literally</p>
            <div>
                <p class="kk-lead">
                    Pilih kopi favoritmu dan atur ukuran, es, serta gulanya. Bayar online atau di kasir, lalu ambil di kedai dengan kode pesanan.
                </p>
                <div class="kk-hero-actions">
                    <a href="{{ route('menu.index') }}" class="btn btn-primary btn-lg">Lihat menu &rarr;</a>
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Masuk</a>
                        <a href="{{ route('register') }}" class="kk-link-arrow">Daftar akun baru</a>
                    @endguest
                    @if ($customer)
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-lg">Pesanan saya</a>
                    @endif
                </div>
            </div>
        </div>

        <ol class="kk-index" aria-label="Langkah singkat">
            <li><b>(01)</b> Pilih</li>
            <li><b>(02)</b> Bayar</li>
            <li><b>(03)</b> Ambil</li>
        </ol>
    </section>

    {{-- ===== Pita nama menu (hiasan) ===== --}}
    <div class="kk-ticker" aria-hidden="true">
        <div class="kk-ticker-track">
            @for ($i = 0; $i < 2; $i++)
                <span>Aren latte</span><span>&#10042;</span><span>Spanish latte</span><span>&#10042;</span><span>Pandan latte</span><span>&#10042;</span><span>Strawberry matcha</span><span>&#10042;</span><span>Butter croissant</span><span>&#10042;</span>
            @endfor
        </div>
    </div>

    {{-- ===== (02) Etalase menu dari database (data: kkFeatured di AppServiceProvider) ===== --}}
    @if ($kkFeatured->isNotEmpty())
        <section class="kk-section-block" aria-labelledby="menu-pilihan">
            <header class="kk-sec-head">
                <span class="kk-sec-num">(02)</span>
                <h2 class="kk-display" id="menu-pilihan">Lagi pengen <span class="kk-italic">yang mana?</span></h2>
                <a href="{{ route('menu.index') }}" class="kk-link-arrow">Lihat semua menu &rarr;</a>
            </header>
            <ul class="kk-featured">
                @foreach ($kkFeatured as $product)
                    <li>
                        <a href="{{ route('menu.index') }}" class="kk-dish">
                            <span class="kk-dish-img">
                                <img src="{{ $product->gambar_url }}" alt="" width="400" height="500" loading="lazy" decoding="async">
                                <span class="kk-dish-no" aria-hidden="true">No. {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="kk-dish-plus" aria-hidden="true">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 5v14M5 12h14"/></svg>
                                </span>
                            </span>
                            <span class="kk-dish-body">
                                <span class="kk-dish-name">{{ $product->nama }}</span>
                                <span class="kk-dish-price">@rp($product->harga_dasar)</span>
                            </span>
                            <span class="kk-dish-meta">
                                <span>{{ $product->category?->nama }}</span>
                                <span>Pesan &rarr;</span>
                            </span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- ===== (03) Kenapa Kopi & Kita ===== --}}
    <section class="kk-section-block" aria-labelledby="kenapa">
        <header class="kk-sec-head">
            <span class="kk-sec-num">(03)</span>
            <h2 class="kk-display" id="kenapa">Ngopi yang <span class="kk-italic">nggak ribet.</span></h2>
        </header>
        <div class="kk-why">
            <blockquote class="kk-quote">
                &ldquo;Kopi enak <mark>nggak harus</mark> nunggu lama.&rdquo;
                <span class="kk-quote-by">&mdash; Kopi &amp; Kita</span>
            </blockquote>
            <ul class="kk-features">
                <li>
                    <span class="kk-mono">A.</span>
                    <h3>Racik sesukamu</h3>
                    <p>Atur sendiri minumanmu sebelum dipesan. Harga langsung terlihat sebelum masuk keranjang.</p>
                    <span class="kk-tags" aria-hidden="true">
                        <span>Regular / Large</span><span>Panas / Dingin</span><span>Level es</span><span>Level gula</span><span>Extra shot</span>
                    </span>
                </li>
                <li>
                    <span class="kk-mono">B.</span>
                    <h3>Bayar fleksibel</h3>
                    <p>QRIS dan e-wallet secara online, atau bayar saat datang ke kasir.</p>
                </li>
                <li>
                    <span class="kk-mono">C.</span>
                    <h3>Status langsung</h3>
                    <p>Halaman pesanan memberi tahu saat minumanmu siap diambil.</p>
                </li>
                <li>
                    <span class="kk-mono">D.</span>
                    <h3>
                        @if ($kkStore)
                            Buka {{ substr($kkStore->jam_buka, 0, 5) }}&ndash;{{ substr($kkStore->jam_tutup, 0, 5) }}
                        @else
                            Buka setiap hari
                        @endif
                    </h3>
                    <p>Pesan dari mana saja, ambil di kedai.</p>
                </li>
            </ul>
        </div>
    </section>

    {{-- ===== (04) Cara pesan ===== --}}
    <section class="kk-section-block" aria-labelledby="cara-pesan">
        <header class="kk-sec-head">
            <span class="kk-sec-num">(04)</span>
            <h2 class="kk-display" id="cara-pesan">Tiga langkah, <span class="kk-italic">beres.</span></h2>
        </header>
        <ol class="kk-steps-home">
            <li>
                <h3>Pilih menu</h3>
                <p>Atur ukuran, suhu, es, gula, dan tambahan. Harga langsung terlihat sebelum masuk keranjang.</p>
            </li>
            <li>
                <h3>Bayar</h3>
                <p>Bayar online dengan QRIS atau e-wallet, atau pilih bayar di kasir dengan QRIS kedai.</p>
            </li>
            <li>
                <h3>Ambil dengan kode</h3>
                <p>Halaman pesanan memberi tahu saat minumanmu siap. Tunjukkan kode pesanan ke kasir.</p>
            </li>
        </ol>
    </section>

    {{-- ===== Ajakan penutup: blok oranye selebar layar ===== --}}
    <section class="kk-cta">
        <div class="container kk-cta-inner">
            <div>
                <h2 class="kk-display">Butuh kafein? <span class="kk-italic">Same.</span></h2>
                <p>Racik minumanmu sekarang, ambil saat sudah siap. Nggak pakai antre.</p>
            </div>
            <a href="{{ route('menu.index') }}" class="btn btn-primary btn-lg">Mulai pesan &rarr;</a>
        </div>
    </section>
@endsection
