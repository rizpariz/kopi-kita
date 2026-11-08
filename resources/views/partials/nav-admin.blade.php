<li class="nav-item"><a class="nav-link @if (request()->is('admin')) active @endif" href="{{ url('/admin') }}">Dashboard</a></li>
<li class="nav-item"><a class="nav-link @if (request()->is('admin/pesanan*')) active @endif" href="{{ url('/admin/pesanan') }}">Pesanan</a></li>
<li class="nav-item"><a class="nav-link @if (request()->is('admin/transaksi*')) active @endif" href="{{ url('/admin/transaksi') }}">Transaksi</a></li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Data</a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="{{ url('/admin/products') }}">Produk</a></li>
        <li><a class="dropdown-item" href="{{ url('/admin/categories') }}">Kategori</a></li>
        <li><a class="dropdown-item" href="{{ url('/admin/vouchers') }}">Voucher</a></li>
        <li><a class="dropdown-item" href="{{ url('/admin/users') }}">Akun Pengguna</a></li>
    </ul>
</li>
<li class="nav-item"><a class="nav-link @if (request()->is('admin/laporan*')) active @endif" href="{{ url('/admin/laporan') }}">Laporan</a></li>
<li class="nav-item"><a class="nav-link @if (request()->is('admin/audit-log*')) active @endif" href="{{ url('/admin/audit-log') }}">Log Audit</a></li>
<li class="nav-item"><a class="nav-link @if (request()->is('admin/pengaturan*')) active @endif" href="{{ url('/admin/pengaturan') }}">Pengaturan</a></li>
<li class="nav-item"><a class="nav-link" href="{{ url('/karyawan') }}">Dashboard operasional</a></li>
