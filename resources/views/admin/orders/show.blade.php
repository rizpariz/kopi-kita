@extends('layouts.admin')

@section('title', 'Pesanan '.$order->kode_pickup)

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="small">Kembali ke pesanan</a>
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
        <div>
            <h1 class="mb-1"><span class="font-monospace">{{ $order->kode_pickup }}</span></h1>
            <p class="mb-0">{{ $order->user->nama }} ({{ $order->user->email }}), {{ $order->created_at->format('d/m/Y H:i') }}
                <x-status-badge :status="$order->status" class="ms-1" />
                @if ($needsRefund) <span class="badge text-bg-danger">Perlu Refund</span> @endif
            </p>
        </div>
        <a href="{{ route('staff.history.show', $order) }}" class="btn btn-outline-secondary">Buka di fitur karyawan</a>
    </div>

    @if ($order->refund || $needsRefund)
        <section class="card mb-4 @if ($needsRefund) border-danger @endif" aria-labelledby="refund-title">
            <div class="card-body">
                <h2 class="h5" id="refund-title">Refund</h2>
                @if ($order->refund)
                    <p class="mb-2">
                        Status <strong>{{ $order->refund->status === \App\Enums\RefundStatus::Selesai ? 'Selesai' : 'Menunggu' }}</strong>,
                        nominal @rp($order->refund->nominal)
                        @if ($order->refund->refund_key) , kunci {{ $order->refund->refund_key }} @endif
                        @if ($order->refund->no_referensi) , referensi {{ $order->refund->no_referensi }} @endif
                        @if ($order->refund->processed_at) , diproses {{ $order->refund->processed_at->format('d/m/Y H:i') }} oleh {{ $order->refund->processor?->nama }} @endif
                    </p>
                @endif

                @if ($needsRefund)
                    <p>Dana @rp($paid->nominal) sudah diterima lewat {{ $paid->metode === \App\Enums\PaymentMethod::Online ? 'pembayaran online ('.$paid->payment_type.')' : 'Bayar di Kasir' }}. Kembalikan secara penuh.</p>
                    <div class="row g-3">
                        @if ($paid->metode === \App\Enums\PaymentMethod::Online)
                            <div class="col-md-5">
                                <form method="POST" action="{{ route('admin.refunds.online', $order) }}" onsubmit="return confirm('Proses refund @rp($paid->nominal) lewat Midtrans?')">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Proses Refund lewat Midtrans</button>
                                </form>
                                <p class="small text-body-secondary mt-2 mb-0">Bila Midtrans menolak, catat refund manual setelah dana dikembalikan.</p>
                            </div>
                        @endif
                        <div class="col-md-7">
                            <form method="POST" action="{{ route('admin.refunds.manual', $order) }}" class="row g-2 align-items-end" novalidate>
                                @csrf
                                <x-field name="no_referensi" label="Nomor referensi transfer" class="col-sm-8 mb-0" maxlength="50" required />
                                <div class="col-auto"><button type="submit" class="btn btn-outline-danger">Catat Refund Manual</button></div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    @include('partials.order-detail')

    <h2 class="h5 mt-4">Detail transaksi</h2>
    <div class="table-responsive">
        <table class="table table-sm small align-middle">
            <thead><tr><th scope="col">Gateway order ID</th><th scope="col">Transaction ID</th><th scope="col">Nominal</th><th scope="col">Diverifikasi</th><th scope="col">Batas</th></tr></thead>
            <tbody>
                @foreach ($order->payments as $payment)
                    <tr>
                        <td class="font-monospace">{{ $payment->gateway_order_id ?? '-' }}</td>
                        <td class="font-monospace">{{ $payment->transaction_id ?? '-' }}</td>
                        <td>@rp($payment->nominal)</td>
                        <td>{{ $payment->verified_at?->format('d/m H:i') ?? '-' }} {{ $payment->verifier?->nama }}</td>
                        <td>{{ $payment->expired_at->format('d/m H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
