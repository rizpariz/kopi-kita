@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <h1 class="mb-4">Checkout</h1>

    @unless ($isOpen)
        <div class="alert alert-warning" role="alert">
            Kedai sedang tidak menerima pesanan. Jam pemesanan {{ substr($setting->jam_buka, 0, 5) }} sampai {{ substr($setting->jam_tutup, 0, 5) }}.
        </div>
    @endunless

    @error('checkout')
        <div class="alert alert-danger" role="alert">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('checkout.store') }}" id="checkout-form" class="row g-4">
        @csrf
        <div class="col-lg-7">
            <h2 class="h5">Pesananmu</h2>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Menu</th>
                            <th scope="col" class="text-end">Jumlah</th>
                            <th scope="col" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $item->product->nama }}</div>
                                    @if ($item->options->isNotEmpty())
                                        <div class="small">{{ $item->options->map(fn ($o) => $o->option->nama)->join(', ') }}</div>
                                    @endif
                                    @if ($item->catatan)
                                        <div class="small text-body-secondary">Catatan: {{ $item->catatan }}</div>
                                    @endif
                                </td>
                                <td class="text-end">{{ $item->qty }}</td>
                                <td class="text-end text-nowrap">Rp{{ number_format($cart->subtotal($item), 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('cart.index') }}">Ubah keranjang</a>
        </div>

        <div class="col-lg-5">
            <div class="card kk-cart-summary">
                <div class="card-body">
                    <label for="voucher-kode" class="form-label fw-semibold">Kode voucher</label>
                    <div class="input-group has-validation mb-3">
                        <input type="text" id="voucher-kode" class="form-control text-uppercase @error('voucher') is-invalid @enderror" maxlength="20" autocomplete="off"
                            aria-describedby="voucher-feedback" value="{{ old('voucher') }}">
                        <button type="button" class="btn btn-outline-primary" id="voucher-apply" data-url="{{ route('checkout.voucher') }}">Pakai</button>
                        <div class="invalid-feedback" id="voucher-feedback">@error('voucher'){{ $message }}@enderror</div>
                    </div>
                    <input type="hidden" name="voucher" id="voucher-input" value="">
                    <p class="small text-success d-none" id="voucher-ok" role="status"></p>

                    <fieldset class="mb-3">
                        <legend class="form-label fw-semibold fs-6">Metode bayar</legend>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="metode" id="metode-online" value="ONLINE"
                                @checked($onlineEnabled && old('metode', 'ONLINE') === 'ONLINE') @disabled(! $onlineEnabled) aria-describedby="metode-online-help">
                            <label class="form-check-label" for="metode-online">Bayar Online</label>
                            <div class="form-text mt-0" id="metode-online-help">
                                {{ $onlineEnabled ? 'QRIS atau e-wallet, bayar dalam '.$setting->batas_bayar_menit.' menit.' : 'Belum tersedia saat ini.' }}
                                @if ($onlineEnabled && ! config('services.midtrans.is_production'))
                                    <span class="d-block text-danger">Mode uji (sandbox): bayar hanya lewat simulator Midtrans, jangan dengan aplikasi sungguhan.</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="metode" id="metode-kasir" value="KASIR"
                                @checked(! $onlineEnabled || old('metode') === 'KASIR') aria-describedby="metode-kasir-help">
                            <label class="form-check-label" for="metode-kasir">Bayar di Kasir</label>
                            <div class="form-text mt-0" id="metode-kasir-help">Scan QRIS kedai di kasir sebelum jam {{ substr($setting->jam_tutup, 0, 5) }}.</div>
                        </div>
                        @error('metode')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </fieldset>

                    <dl class="mb-0">
                        <div class="d-flex justify-content-between">
                            <dt class="fw-normal">Subtotal</dt>
                            <dd class="mb-1" id="sum-subtotal">Rp{{ number_format($summary['subtotal'], 0, ',', '.') }}</dd>
                        </div>
                        <div class="d-flex justify-content-between">
                            <dt class="fw-normal">Diskon</dt>
                            <dd class="mb-1" id="sum-diskon">Rp0</dd>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline border-top pt-2 mt-2">
                            <dt>Total</dt>
                            <dd class="mb-0 kk-price fs-4" id="sum-total">Rp{{ number_format($summary['subtotal'], 0, ',', '.') }}</dd>
                        </div>
                    </dl>

                    <button type="submit" class="btn btn-primary btn-lg w-100 mt-3" id="checkout-submit" @disabled(! $isOpen)>Buat Pesanan</button>
                    <p class="small text-body-secondary mt-2 mb-0">Total dihitung ulang oleh sistem saat pesanan dibuat.</p>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/checkout.js') }}"></script>
@endpush
