@extends('layouts.admin')

@section('title', 'Voucher')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="mb-0">Voucher</h1>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">Tambah voucher</a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead><tr><th scope="col">Kode</th><th scope="col">Potongan</th><th scope="col">Terpakai</th><th scope="col">Berlaku</th><th scope="col">Status</th><th scope="col"><span class="visually-hidden">Aksi</span></th></tr></thead>
            <tbody>
                @forelse ($vouchers as $voucher)
                    @php($state = now()->lt($voucher->berlaku_mulai) ? ['Belum berlaku', 'text-bg-light border'] : (now()->gt($voucher->berlaku_sampai) ? ['Kedaluwarsa', 'text-bg-secondary'] : ($voucher->terpakai >= $voucher->kuota ? ['Kuota habis', 'text-bg-warning'] : ['Aktif', 'text-bg-success'])))
                    <tr>
                        <td class="font-monospace fw-semibold">{{ $voucher->kode }}</td>
                        <td>@if ($voucher->jenis === \App\Enums\VoucherType::Persen){{ (int) $voucher->nilai }}%@else @rp($voucher->nilai) @endif</td>
                        <td>{{ $voucher->terpakai }} / {{ $voucher->kuota }}</td>
                        <td class="small text-nowrap">{{ $voucher->berlaku_mulai->format('d/m/Y H:i') }} sampai {{ $voucher->berlaku_sampai->format('d/m/Y H:i') }}</td>
                        <td><span class="badge {{ $state[1] }}">{{ $state[0] }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-delete-url="{{ route('admin.vouchers.destroy', $voucher) }}" data-delete-name="voucher {{ $voucher->kode }}">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-body-secondary">Belum ada voucher.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $vouchers->links() }}
@endsection
