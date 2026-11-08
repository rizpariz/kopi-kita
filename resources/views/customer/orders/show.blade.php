@extends('layouts.app')

@section('title', 'Pesanan '.$order->kode_pickup)

@php
    $steps = [
        \App\Enums\OrderStatus::MenungguPembayaran,
        \App\Enums\OrderStatus::Dibayar,
        \App\Enums\OrderStatus::Diproses,
        \App\Enums\OrderStatus::SiapDiambil,
        \App\Enums\OrderStatus::Selesai,
    ];
    $statuses = collect(\App\Enums\OrderStatus::cases())->mapWithKeys(fn ($s) => [$s->value => ['label' => $s->label(), 'badge' => $s->badgeClass()]]);
    $show = fn (string $states) => in_array($order->status->value, explode(' ', $states), true) ? '' : ' d-none';
    $isKasir = $payment?->metode === \App\Enums\PaymentMethod::Kasir;
    $sandbox = ! config('services.midtrans.is_production');
    $hasToken = $payment && \Illuminate\Support\Facades\Cache::has("snap_token:{$payment->id}");
@endphp

@section('content')
    <div id="order-page" data-status="{{ $order->status->value }}" data-status-url="{{ route('orders.status', $order) }}"
        data-expired="{{ $payment?->expired_at?->toIso8601String() }}" data-metode="{{ $payment?->metode->value }}"
        data-pay-url="{{ route('orders.pay-online', $order) }}">

        <div class="kk-order-layout">
            <div class="kk-area-ticket">
                <section class="kk-ticket text-center" aria-labelledby="kode-label">
                    <h1 class="mb-2">
                        <span class="d-block fs-6 fw-normal text-body-secondary mb-1" id="kode-label">Kode pesanan</span>
                        <span class="d-block display-4 font-monospace fw-bold kk-code" id="kode-value">{{ $order->kode_pickup }}</span>
                    </h1>
                    <button type="button" class="btn btn-sm btn-outline-primary mb-3" id="copy-code" data-code="{{ $order->kode_pickup }}">Salin kode</button><br>
                    <span class="badge fs-6 {{ $order->status->badgeClass() }}" id="order-badge">{{ $order->status->label() }}</span>
                    <p class="mt-3 mb-0">Total <span class="kk-price fs-4 ms-1">Rp{{ number_format($order->total, 0, ',', '.') }}</span></p>
                </section>
            </div>
            <div class="kk-area-steps">
                <ol class="kk-steps list-unstyled mb-0{{ $show('MENUNGGU_PEMBAYARAN DIBAYAR DIPROSES SIAP_DIAMBIL SELESAI') }}" id="order-steps" data-states="MENUNGGU_PEMBAYARAN DIBAYAR DIPROSES SIAP_DIAMBIL SELESAI">
                    @foreach ($steps as $step)
                        <li data-step="{{ $step->value }}">{{ $step->label() }}</li>
                    @endforeach
                </ol>
            </div>

            <div class="kk-area-main">
                <div role="status" aria-live="polite" id="order-live">
                    <div class="alert alert-success{{ $show('DIBAYAR DIPROSES') }}" data-states="DIBAYAR DIPROSES">
                        <h2 class="h5 alert-heading">Pembayaran Berhasil</h2>
                        <p class="mb-0">Pesananmu sudah masuk antrean barista. Halaman ini akan berubah saat pesanan siap.</p>
                    </div>
                    <div class="alert alert-info{{ $show('SIAP_DIAMBIL') }}" data-states="SIAP_DIAMBIL">
                        <h2 class="h5 alert-heading">Pesanan siap diambil</h2>
                        <p class="mb-0">Datang ke kasir dan tunjukkan kode <strong class="font-monospace">{{ $order->kode_pickup }}</strong>.</p>
                    </div>
                    <div class="alert alert-secondary{{ $show('SELESAI') }}" data-states="SELESAI">
                        <h2 class="h5 alert-heading">Pesanan selesai</h2>
                        <p class="mb-0">Terima kasih sudah memesan di {{ $setting->nama_kedai }}.</p>
                    </div>
                    <div class="alert alert-danger{{ $show('DIBATALKAN') }}" data-states="DIBATALKAN">
                        <h2 class="h5 alert-heading">Pesanan dibatalkan</h2>
                        <p class="mb-0" id="order-reason">{{ $order->alasan_batal ? 'Alasan dari kedai: '.$order->alasan_batal : 'Pesanan ini sudah dibatalkan.' }}</p>
                    </div>
                    <div class="alert alert-dark{{ $show('KEDALUWARSA') }}" data-states="KEDALUWARSA">
                        <h2 class="h5 alert-heading">Pesanan kedaluwarsa</h2>
                        <p class="mb-0">Batas pembayaran sudah lewat. Silakan pesan ulang dari riwayat pesanan.</p>
                    </div>
                </div>

                <section class="{{ $show('MENUNGGU_PEMBAYARAN') }}" data-states="MENUNGGU_PEMBAYARAN" aria-labelledby="bayar-title">
                    <h2 class="h4" id="bayar-title">{{ $isKasir ? 'Bayar di kasir' : 'Bayar online' }}</h2>
                    <div class="alert alert-warning">
                        Bayar sebelum <strong>{{ $payment?->expired_at?->format('H:i') }}</strong>
                        <span id="countdown" class="ms-1"></span>
                    </div>

                    @if ($isKasir)
                        <div class="d-flex flex-column flex-sm-row gap-4 align-items-sm-start">
                            <img src="{{ $setting->qris_url }}" alt="QRIS {{ $setting->nama_kedai }}" width="200" height="200" class="kk-qris border rounded-3">
                            <ol class="ps-3 mb-0">
                                <li>Scan QRIS ini dengan aplikasi e-wallet atau mobile banking.</li>
                                <li>Masukkan nominal tepat <strong>Rp{{ number_format($order->total, 0, ',', '.') }}</strong>.</li>
                                <li>Tunjukkan kode pesanan dan bukti bayar ke kasir.</li>
                                <li>Halaman ini berubah otomatis setelah kasir mengonfirmasi.</li>
                            </ol>
                        </div>
                        @if ($onlineEnabled)
                            <button type="button" class="btn btn-outline-primary mt-3 js-pay-online">Ganti ke Bayar Online</button>
                        @endif
                    @else
                        <p>Bayar dengan QRIS, GoPay, atau ShopeePay di jendela pembayaran Midtrans. Jika jendelanya tertutup, buka lagi dengan tombol di bawah.</p>
                        @if ($sandbox)
                            <div class="alert alert-info small">
                                Mode uji (sandbox). Jangan bayar dengan aplikasi e-wallet atau bank sungguhan. Pakai
                                <a href="https://simulator.sandbox.midtrans.com/v2/qris/index" target="_blank" rel="noopener">simulator QRIS Midtrans</a>.
                            </div>
                        @endif
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-primary js-pay-online">{{ $hasToken ? 'Lanjutkan Pembayaran' : 'Coba Bayar Lagi' }}</button>
                            <button type="button" class="btn btn-outline-primary" id="switch-cashier" data-url="{{ route('orders.pay-cashier', $order) }}">Ganti ke Bayar di Kasir</button>
                        </div>
                    @endif

                    <div class="d-flex flex-wrap gap-2 mt-4">
                        <button type="button" class="btn btn-outline-secondary" id="check-status">Cek Status</button>
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancel-modal">Batalkan Pesanan</button>
                    </div>
                </section>

                <div data-states="DIBAYAR DIPROSES SIAP_DIAMBIL SELESAI" class="mb-3{{ $show('DIBAYAR DIPROSES SIAP_DIAMBIL SELESAI') }}">
                    <a href="{{ route('orders.receipt', $order) }}" class="btn btn-outline-primary">Lihat Struk</a>
                </div>

                <section class="mt-4" aria-labelledby="rincian-title">
                    <h2 class="h5" id="rincian-title">Rincian</h2>
                    <ul class="list-group mb-2">
                        @foreach ($order->items as $item)
                            <li class="list-group-item d-flex justify-content-between gap-3">
                                <div>
                                    <div class="fw-semibold">{{ $item->qty }}&times; {{ $item->nama_snapshot }}</div>
                                    @if ($item->options->isNotEmpty())
                                        <div class="small">{{ $item->options->pluck('nama_snapshot')->join(', ') }}</div>
                                    @endif
                                    @if ($item->catatan)
                                        <div class="small text-body-secondary">Catatan: {{ $item->catatan }}</div>
                                    @endif
                                </div>
                                <span class="text-nowrap">Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <dl class="small">
                        <div class="d-flex justify-content-between"><dt class="fw-normal">Subtotal</dt><dd class="mb-0">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</dd></div>
                        @if ((int) $order->diskon > 0)
                            <div class="d-flex justify-content-between"><dt class="fw-normal">Diskon</dt><dd class="mb-0">-Rp{{ number_format($order->diskon, 0, ',', '.') }}</dd></div>
                        @endif
                        <div class="d-flex justify-content-between fw-semibold"><dt>Total</dt><dd class="mb-0">Rp{{ number_format($order->total, 0, ',', '.') }}</dd></div>
                    </dl>
                    <a href="{{ route('orders.index') }}">Lihat semua pesanan</a>
                </section>
            </div>
        </div>
    </div>

    <div class="modal fade" id="cancel-modal" tabindex="-1" aria-labelledby="cancel-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="POST" action="{{ route('orders.cancel', $order) }}">
                @csrf
                <div class="modal-header">
                    <h2 class="modal-title h5" id="cancel-title">Batalkan pesanan {{ $order->kode_pickup }}?</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">Pesanan yang dibatalkan tidak dapat dipulihkan. Kamu bisa memesan ulang dari riwayat pesanan.</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Jangan batalkan</button>
                    <button type="submit" class="btn btn-danger">Ya, batalkan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($onlineEnabled && $order->status === \App\Enums\OrderStatus::MenungguPembayaran)
        <script src="{{ config('services.midtrans.snap_js') }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
    <script>window.kkStatuses = {{ Js::from($statuses) }};</script>
    <script src="{{ asset('js/order.js') }}"></script>
@endpush
