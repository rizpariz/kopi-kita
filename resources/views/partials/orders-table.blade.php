{{-- Tabel pesanan untuk riwayat karyawan dan monitoring admin. --}}
@if ($orders->isEmpty())
    <p class="text-body-secondary py-4">Tidak ada pesanan yang cocok dengan filter.</p>
@else
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead>
                <tr>
                    <th scope="col">Kode</th>
                    <th scope="col">Pelanggan</th>
                    <th scope="col">Dibuat</th>
                    <th scope="col" class="text-end">Total</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td><a href="{{ route($detailRoute, $order) }}" class="font-monospace fw-semibold">{{ $order->kode_pickup }}</a></td>
                        <td>{{ $order->user->nama }}</td>
                        <td class="text-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end text-nowrap">@rp($order->total)</td>
                        <td class="text-nowrap">
                            <x-status-badge :status="$order->status" />
                            @if (in_array($order->id, $refundIds ?? [], true))
                                <span class="badge text-bg-danger">Perlu Refund</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $orders->links() }}
@endif
