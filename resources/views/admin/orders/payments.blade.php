@extends('layouts.admin')

@section('title', 'Transaksi')

@section('content')
    <h1 class="mb-3">Transaksi</h1>
    <form method="GET" class="row g-2 align-items-end mb-3">
        <x-field name="status" label="Status" type="select" :value="$filter['status'] ?? ''" class="col-6 col-md-3 col-xl-2 mb-0"
            :options="['' => 'Semua status'] + collect(\App\Enums\PaymentStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])->all()" />
        <x-field name="metode" label="Metode" type="select" :value="$filter['metode'] ?? ''" class="col-6 col-md-2 mb-0"
            :options="['' => 'Semua', 'ONLINE' => 'Online', 'KASIR' => 'Kasir']" />
        <div class="col-auto"><button class="btn btn-primary" type="submit">Terapkan</button></div>
    </form>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-sm align-middle">
            <thead><tr><th scope="col">Pesanan</th><th scope="col">Metode</th><th scope="col">Gateway order ID</th><th scope="col">Transaction ID</th><th scope="col">Tipe</th><th scope="col" class="text-end">Nominal</th><th scope="col">Status</th><th scope="col">Dibuat</th></tr></thead>
            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $payment->order) }}" class="font-monospace">{{ $payment->order->kode_pickup }}</a></td>
                        <td>{{ $payment->metode === \App\Enums\PaymentMethod::Kasir ? 'Kasir' : 'Online' }}</td>
                        <td class="font-monospace small">{{ $payment->gateway_order_id ?? '-' }}</td>
                        <td class="font-monospace small text-break">{{ $payment->transaction_id ?? '-' }}</td>
                        <td>{{ $payment->payment_type ?? '-' }}</td>
                        <td class="text-end text-nowrap">@rp($payment->nominal)</td>
                        <td>{{ $payment->status->label() }}</td>
                        <td class="text-nowrap small">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-body-secondary">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $payments->links() }}
@endsection
