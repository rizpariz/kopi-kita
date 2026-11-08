@extends('layouts.admin')

@section('title', $user->exists ? 'Ubah Akun' : 'Tambah Akun')

@section('content')
    <a href="{{ route('admin.users.index') }}" class="small">Kembali ke daftar akun</a>
    <h1 class="mb-3">{{ $user->exists ? 'Ubah akun '.$user->nama : 'Tambah akun staf' }}</h1>

    <div class="row g-4">
        <div class="col-lg-6">
            <form method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}" class="card card-body" novalidate>
                @csrf
                @if ($user->exists) @method('PUT') @endif
                <x-field name="nama" label="Nama lengkap" :value="$user->nama" required maxlength="100" />
                <x-field name="email" label="Email" type="email" :value="$user->email" required maxlength="150" />
                <x-field name="no_hp" label="Nomor HP" type="tel" :value="$user->no_hp" required maxlength="20" help="Diawali 08 atau +628." />
                @if ($user->is(auth()->user()))
                    <input type="hidden" name="role" value="{{ $user->role->value }}">
                @endif
                <x-field name="role" label="Role" type="select" :value="$user->role?->value"
                    :options="collect(\App\Enums\Role::cases())->mapWithKeys(fn ($r) => [$r->value => $r->label()])->all()"
                    :disabled="$user->is(auth()->user())" :help="$user->is(auth()->user()) ? 'Role akun sendiri tidak dapat diubah.' : null" />
                @unless ($user->exists)
                    <x-field name="password" label="Password" type="password" required autocomplete="new-password" help="8 sampai 64 karakter." />
                    <x-field name="password_confirmation" label="Ulangi password" type="password" required autocomplete="new-password" />
                @endunless
                <div><button type="submit" class="btn btn-primary">Simpan</button></div>
            </form>
        </div>
        @if ($user->exists)
            <div class="col-lg-6">
                <form method="POST" action="{{ route('admin.users.password', $user) }}" class="card card-body" novalidate>
                    @csrf
                    @method('PUT')
                    <h2 class="h5">Atur ulang password</h2>
                    <p class="small text-body-secondary">Beri tahu password baru ke pemilik akun secara langsung.</p>
                    <x-field name="password" label="Password baru" type="password" required autocomplete="new-password" />
                    <x-field name="password_confirmation" label="Ulangi password baru" type="password" required autocomplete="new-password" />
                    <div><button type="submit" class="btn btn-outline-primary">Atur ulang password</button></div>
                </form>
            </div>
        @endif
    </div>
@endsection
