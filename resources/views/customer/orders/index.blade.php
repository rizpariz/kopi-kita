@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <h1 class="mb-4">Pesanan saya</h1>

    @if ($orders->isEmpty())
        <div class="text-center py-5">
            <h2 class="h4">Belum ada pesanan</h2>
            <p class="text-body-secondary">Pesanan yang kamu buat akan muncul di sini.</p>
            <a href="{{ route('menu.index') }}" class="btn btn-primary">Lihat menu</a>
        </div>
    @else
        {{-- Ponsel: daftar kartu; md ke atas: tabel. --}}
        <ul class="list-group d-md-none mb-3">
            @foreach ($orders as $order)
                <li class="list-group-item py-3">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <a href="{{ route('orders.show', $order) }}" class="font-monospace fw-semibold fs-5">{{ $order->kode_pickup }}</a>
                        <x-status-badge :status="$order->status" />
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-2 mt-1">
                        <span class="small text-body-secondary">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        <span class="fw-semibold">@rp($order->total)</span>
                    </div>
                    <div class="d-flex gap-2 mt-2">
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary flex-fill">Lihat</a>
                        <form method="POST" action="{{ route('orders.reorder', $order) }}" class="flex-fill d-flex">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-primary flex-fill" aria-label="Pesan ulang {{ $order->kode_pickup }}">Pesan Ulang</button>
                        </form>
                    </div>
                </li>
            @endforeach
        </ul>
        <div class="table-responsive d-none d-md-block">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">Kode</th>
                        <th scope="col">Tanggal</th>
                        <th scope="col" class="text-end">Total</th>
                        <th scope="col">Status</th>
                        <th scope="col"><span class="visually-hidden">Aksi</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td><a href="{{ route('orders.show', $order) }}" class="font-monospace fw-semibold">{{ $order->kode_pickup }}</a></td>
                            <td class="text-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-end text-nowrap">Rp{{ number_format($order->total, 0, ',', '.') }}</td>
                            <td><span class="badge {{ $order->status->badgeClass() }}">{{ $order->status->label() }}</span></td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('orders.reorder', $order) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-primary text-nowrap" aria-label="Pesan ulang {{ $order->kode_pickup }}">Pesan Ulang</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    @endif
@endsection
