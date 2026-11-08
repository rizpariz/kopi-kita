@extends('layouts.app')

@section('title', 'Kebijakan Privasi')

@section('content')
    <article class="mx-auto" style="max-width: 48rem">
        <h1 class="h3">Kebijakan Privasi</h1>
        <p class="text-body-secondary">Versi 1.0, berlaku sejak Oktober 2026.</p>

        <h2 class="h5 mt-4">Data yang kami kumpulkan</h2>
        <p>Saat mendaftar kami menyimpan nama, email, nomor HP, dan password Anda. Password disimpan dalam bentuk hash sehingga tidak dapat dibaca siapa pun. Kami juga menyimpan riwayat pesanan, isi keranjang, dan data pembayaran yang diperlukan untuk memproses pesanan.</p>

        <h2 class="h5 mt-4">Tujuan penggunaan</h2>
        <p>Data dipakai untuk membuat dan memproses pesanan, mengonfirmasi pembayaran, menghubungi Anda terkait pesanan, dan menyusun laporan penjualan kedai. Kami tidak menjual atau membagikan data Anda kepada pihak lain, kecuali data transaksi yang diperlukan penyedia pembayaran Midtrans untuk memproses pembayaran online.</p>

        <h2 class="h5 mt-4">Hak Anda</h2>
        <p>Anda dapat melihat dan mengubah nama serta nomor HP melalui halaman Profil. Untuk meminta salinan atau penghapusan data, hubungi pengelola kedai melalui kontak yang tertera di kedai.</p>

        <h2 class="h5 mt-4">Keamanan</h2>
        <p>Akses ke data dibatasi sesuai peran pengguna, dan setiap aksi penting oleh staf dicatat dalam log audit.</p>
    </article>
@endsection
