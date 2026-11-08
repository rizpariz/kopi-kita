@extends('layouts.admin')

@section('title', 'Pengaturan Kedai')

@section('content')
    <h1 class="mb-3">Pengaturan kedai</h1>
    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="card card-body kk-form" novalidate>
        @csrf
        @method('PUT')
        <x-field name="pemesanan_dibuka" label="Pemesanan dibuka" type="switch" :value="$setting->pemesanan_dibuka"
            help="Matikan untuk menutup checkout sementara, misalnya saat stok habis." />
        <x-field name="nama_kedai" label="Nama kedai" :value="$setting->nama_kedai" required maxlength="100" />
        <x-field name="alamat" label="Alamat" type="textarea" :value="$setting->alamat" required maxlength="500" rows="2" />
        <x-field name="kontak" label="Kontak" :value="$setting->kontak" required maxlength="50" />
        <div class="row">
            <x-field name="jam_buka" label="Jam buka" type="time" :value="substr($setting->jam_buka, 0, 5)" class="col-sm-4" required />
            <x-field name="jam_tutup" label="Jam tutup" type="time" :value="substr($setting->jam_tutup, 0, 5)" class="col-sm-4" required />
            <x-field name="batas_bayar_menit" label="Batas bayar online (menit)" type="number" :value="$setting->batas_bayar_menit" class="col-sm-4" required min="5" max="60" />
        </div>
        <div class="d-flex gap-3 align-items-start">
            <img src="{{ $setting->qris_url }}" alt="Pratinjau QRIS kedai" width="140" height="140" class="kk-qris border rounded-3" id="qris-preview">
            <x-field name="qris_image" label="Gambar QRIS statis" type="file" accept="image/jpeg,image/png,image/webp" class="flex-grow-1"
                help="Dipakai untuk Bayar di Kasir. JPG, PNG, atau WEBP, maksimal 2 MB." />
        </div>
        <div><button type="submit" class="btn btn-primary">Simpan pengaturan</button></div>
    </form>
@endsection

@push('scripts')
    <script>
        $('input[name=qris_image]').on('change', function () {
            if (this.files[0]) {
                $('#qris-preview').attr('src', URL.createObjectURL(this.files[0]));
            }
        });
    </script>
@endpush
