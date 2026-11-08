{{-- Rincian item, pembayaran, dan riwayat status; dipakai detail karyawan dan admin. --}}
<div class="row g-4">
    <div class="col-lg-6">
        <h2 class="h5">Item</h2>
        <ul class="list-group mb-2">
            @foreach ($order->items as $item)
                <li class="list-group-item d-flex justify-content-between gap-3">
                    <div>
                        <div class="fw-semibold">{{ $item->qty }}&times; {{ $item->nama_snapshot }}</div>
                        @if ($item->options->isNotEmpty())
                            <div class="small">{{ $item->options->pluck('nama_snapshot')->join(', ') }}</div>
                        @endif
                        @if ($item->catatan)
                            <div class="small kk-note">Catatan: {{ $item->catatan }}</div>
                        @endif
                    </div>
                    <span class="text-nowrap">@rp($item->subtotal)</span>
                </li>
            @endforeach
        </ul>
        <dl class="small">
            <div class="d-flex justify-content-between"><dt class="fw-normal">Subtotal</dt><dd class="mb-0">@rp($order->subtotal)</dd></div>
            <div class="d-flex justify-content-between"><dt class="fw-normal">Diskon{{ $order->voucher ? ' ('.$order->voucher->kode.')' : '' }}</dt><dd class="mb-0">-@rp($order->diskon)</dd></div>
            <div class="d-flex justify-content-between fw-semibold"><dt>Total</dt><dd class="mb-0">@rp($order->total)</dd></div>
        </dl>
        @if ($order->alasan_batal)
            <div class="alert alert-danger small">Dibatalkan {{ $order->cancelledBy?->nama }}: {{ $order->alasan_batal }}</div>
        @endif
    </div>
    <div class="col-lg-6">
        <h2 class="h5">Pembayaran</h2>
        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead><tr><th scope="col">Metode</th><th scope="col">Status</th><th scope="col">Tipe</th><th scope="col">Waktu</th></tr></thead>
                <tbody>
                    @foreach ($order->payments as $payment)
                        <tr>
                            <td>{{ $payment->metode === \App\Enums\PaymentMethod::Kasir ? 'Kasir' : 'Online' }}</td>
                            <td>{{ $payment->status->label() }}</td>
                            <td>{{ $payment->payment_type ?? '-' }}</td>
                            <td class="text-nowrap small">{{ ($payment->verified_at ?? $payment->created_at)->format('d/m H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <h2 class="h5 mt-3">Riwayat status</h2>
        <ol class="list-unstyled small mb-0">
            @foreach ($order->statusLogs as $log)
                <li class="mb-1">
                    <span class="text-nowrap">{{ $log->changed_at->format('d/m H:i') }}</span>,
                    {{ $log->status_baru->label() }}
                    <span class="text-body-secondary">oleh {{ $log->actor?->nama ?? 'sistem' }}</span>
                </li>
            @endforeach
        </ol>
    </div>
</div>
