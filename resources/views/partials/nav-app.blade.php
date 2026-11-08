@auth
    <li class="nav-item"><a class="nav-link @if (request()->is('menu*')) active @endif" href="{{ url('/menu') }}">Menu</a></li>
    <li class="nav-item">
        <a class="nav-link @if (request()->is('keranjang*')) active @endif" href="{{ url('/keranjang') }}">
            Keranjang <span class="badge text-bg-light js-cart-count">{{ $cartCount ?? 0 }}</span>
        </a>
    </li>
    <li class="nav-item"><a class="nav-link @if (request()->is('pesanan*')) active @endif" href="{{ url('/pesanan') }}">Pesanan</a></li>
    <li class="nav-item"><a class="nav-link @if (request()->is('profil*')) active @endif" href="{{ url('/profil') }}">{{ auth()->user()->nama }}</a></li>
    @include('partials.logout')
@else
    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Daftar</a></li>
@endauth
