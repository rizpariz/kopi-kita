@extends('layouts.app')

@section('title', 'Keranjang')

@section('content')
    <h1 class="mb-4">Keranjang</h1>

    <div id="cart-empty" class="text-center py-5 @if ($items->isNotEmpty()) d-none @endif">
        <h2 class="h4">Keranjang masih kosong</h2>
        <p class="text-body-secondary">Tambahkan menu dari katalog untuk mulai memesan.</p>
        <a href="{{ route('menu.index') }}" class="btn btn-primary">Lihat menu</a>
    </div>

    @if ($items->isNotEmpty())
        <div class="row g-4" id="cart-content">
            <div class="col-lg-8">
                <ul class="list-group">
                    @foreach ($items as $item)
                        <li class="list-group-item py-3" id="cart-item-{{ $item->id }}" data-url="{{ route('cart.items.update', $item) }}">
                            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-between">
                                <div class="flex-grow-1">
                                    <h2 class="h5 mb-1">
                                        {{ $item->product->nama }}
                                        @unless ($item->product->tersedia)
                                            <span class="badge text-bg-dark align-middle">Habis</span>
                                        @endunless
                                    </h2>
                                    @if ($item->options->isNotEmpty())
                                        <p class="small mb-1">{{ $item->options->map(fn ($o) => $o->option->nama)->join(', ') }}</p>
                                    @endif
                                    @if ($item->catatan)
                                        <p class="small text-body-secondary mb-1">Catatan: {{ $item->catatan }}</p>
                                    @endif
                                    <p class="small text-body-secondary mb-0">{{ 'Rp'.number_format($cart->unitPrice($item), 0, ',', '.') }} per item</p>
                                    @unless ($item->product->tersedia)
                                        <p class="small text-danger mb-0">Menu ini sedang habis. Hapus dari keranjang sebelum checkout.</p>
                                    @endunless
                                </div>
                                <div class="d-flex flex-wrap align-items-center gap-3 flex-shrink-0">
                                    <div class="input-group flex-nowrap w-auto" role="group" aria-label="Jumlah {{ $item->product->nama }}">
                                        <button type="button" class="btn btn-outline-secondary js-step" data-step="-1" aria-label="Kurangi jumlah {{ $item->product->nama }}">&minus;</button>
                                        <label for="qty-{{ $item->id }}" class="visually-hidden">Jumlah {{ $item->product->nama }}</label>
                                        <input type="number" class="form-control kk-qty js-qty" id="qty-{{ $item->id }}" value="{{ $item->qty }}" min="1" max="10" inputmode="numeric">
                                        <button type="button" class="btn btn-outline-secondary js-step" data-step="1" aria-label="Tambah jumlah {{ $item->product->nama }}">+</button>
                                    </div>
                                    <span class="kk-price text-end kk-line-total" id="subtotal-{{ $item->id }}">Rp{{ number_format($cart->subtotal($item), 0, ',', '.') }}</span>
                                    <button type="button" class="btn btn-sm btn-outline-danger js-remove" aria-label="Hapus {{ $item->product->nama }}">Hapus</button>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('menu.index') }}" class="btn btn-link px-0 mt-2">Tambah menu lain</a>
            </div>
            <div class="col-lg-4">
                <div class="card kk-cart-summary">
                    <div class="card-body">
                        <h2 class="h5">Ringkasan</h2>
                        <dl class="d-flex justify-content-between mb-1">
                            <dt class="fw-normal">Jumlah item</dt>
                            <dd class="mb-0" id="cart-summary-count">{{ $summary['count'] }}</dd>
                        </dl>
                        <dl class="d-flex justify-content-between align-items-baseline mb-3">
                            <dt class="fw-semibold">Subtotal</dt>
                            <dd class="mb-0 kk-price fs-5" id="cart-summary-subtotal">Rp{{ number_format($summary['subtotal'], 0, ',', '.') }}</dd>
                        </dl>
                        <p class="small text-body-secondary">Voucher dan metode bayar dipilih di halaman checkout.</p>
                        <a href="{{ url('/checkout') }}" class="btn btn-primary w-100">Lanjut ke checkout</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="visually-hidden" role="status" aria-live="polite" id="cart-live"></div>
    @endif
@endsection

@push('scripts')
    <script src="{{ asset('js/cart.js') }}"></script>
@endpush
