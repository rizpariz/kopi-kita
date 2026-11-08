@extends('layouts.admin')

@section('title', 'Akun Pengguna')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <h1 class="mb-0">Akun pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Tambah akun staf</a>
    </div>

    <form method="GET" class="row g-2 align-items-end mb-3" role="search">
        <x-field name="q" label="Nama atau email" :value="$filter['q'] ?? ''" class="col-sm-6 col-lg-4 mb-0" />
        <x-field name="role" label="Role" type="select" :value="$filter['role'] ?? ''" class="col-sm-4 col-lg-3 mb-0"
            :options="['' => 'Semua role'] + collect(\App\Enums\Role::cases())->mapWithKeys(fn ($r) => [$r->value => $r->label()])->all()" />
        <div class="col-auto"><button class="btn btn-primary" type="submit">Cari</button></div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead><tr><th scope="col">Nama</th><th scope="col">Email</th><th scope="col">Role</th><th scope="col">Status</th><th scope="col"><span class="visually-hidden">Aksi</span></th></tr></thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->nama }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->label() }}</td>
                        <td><span class="badge {{ $user->aktif ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $user->aktif ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Ubah</a>
                            @unless ($user->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $user->aktif ? 'btn-outline-danger' : 'btn-outline-success' }}">{{ $user->aktif ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-body-secondary">Tidak ada akun yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $users->links() }}
@endsection
