@extends('layouts.admin')

@section('title', $voucher->exists ? 'Ubah Voucher' : 'Tambah Voucher')

@section('content')
    <a href="{{ route('admin.vouchers.index') }}" class="small">Kembali ke voucher</a>
    <h1 class="mb-3">{{ $voucher->exists ? 'Ubah voucher '.$voucher->kode : 'Tambah voucher' }}</h1>
    <form method="POST" action="{{ $voucher->exists ? route('admin.vouchers.update', $voucher) : route('admin.vouchers.store') }}" class="card card-body kk-form" novalidate>
        @csrf
        @if ($voucher->exists) @method('PUT') @endif
        <x-field name="kode" label="Kode" :value="$voucher->kode" required maxlength="20" help="Huruf besar dan angka, misalnya HEMAT10." />
        <div class="row">
            <x-field name="jenis" label="Jenis" type="select" :value="$voucher->jenis?->value" class="col-sm-6" :options="['PERSEN' => 'Persen (%)', 'NOMINAL' => 'Nominal (Rp)']" />
            <x-field name="nilai" label="Nilai" type="number" :value="$voucher->exists ? (int) $voucher->nilai : null" class="col-sm-6" required min="1" help="Persen 1 sampai 100, atau potongan Rupiah." />
        </div>
        <x-field name="kuota" label="Kuota pemakaian" type="number" :value="$voucher->kuota" required :min="max(1, $voucher->terpakai)"
            :help="$voucher->exists ? 'Sudah terpakai '.$voucher->terpakai.' kali; kuota tidak boleh di bawah angka itu.' : null" />
        <div class="row">
            <x-field name="berlaku_mulai" label="Berlaku mulai" type="datetime-local" :value="$voucher->berlaku_mulai?->format('Y-m-d\TH:i')" class="col-sm-6" required />
            <x-field name="berlaku_sampai" label="Berlaku sampai" type="datetime-local" :value="$voucher->berlaku_sampai?->format('Y-m-d\TH:i')" class="col-sm-6" required />
        </div>
        <div><button type="submit" class="btn btn-primary">Simpan</button></div>
    </form>
@endsection
