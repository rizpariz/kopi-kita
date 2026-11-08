<li class="nav-item"><a class="nav-link @if (request()->is('karyawan')) active @endif" href="{{ url('/karyawan') }}">Antrean</a></li>
<li class="nav-item"><a class="nav-link @if (request()->is('karyawan/kasir*')) active @endif" href="{{ url('/karyawan/kasir') }}">Kasir</a></li>
<li class="nav-item"><a class="nav-link @if (request()->is('karyawan/pengambilan*')) active @endif" href="{{ url('/karyawan/pengambilan') }}">Pengambilan</a></li>
<li class="nav-item"><a class="nav-link @if (request()->is('karyawan/ketersediaan*')) active @endif" href="{{ url('/karyawan/ketersediaan') }}">Ketersediaan</a></li>
<li class="nav-item"><a class="nav-link @if (request()->is('karyawan/riwayat*')) active @endif" href="{{ url('/karyawan/riwayat') }}">Riwayat</a></li>
@if (auth()->user()->hasRole(\App\Enums\Role::SuperAdmin))
    <li class="nav-item"><a class="nav-link" href="{{ url('/admin') }}">Panel Admin</a></li>
@endif
