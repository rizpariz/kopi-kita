@extends('layouts.admin')

@section('title', 'Log Audit')

@section('content')
    <h1 class="mb-3">Log audit</h1>
    <form method="GET" class="row g-2 align-items-end mb-3">
        <x-field name="aksi" label="Aksi" type="select" :value="$filter['aksi'] ?? ''" class="col-sm-5 col-lg-3 mb-0"
            :options="['' => 'Semua aksi'] + array_combine($actions, $actions)" />
        <x-field name="user_id" label="Pelaku" type="select" :value="$filter['user_id'] ?? ''" class="col-sm-5 col-lg-3 mb-0"
            :options="['' => 'Semua pelaku'] + $actors->pluck('nama', 'id')->all()" />
        <div class="col-auto"><button class="btn btn-primary" type="submit">Terapkan</button></div>
    </form>
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead><tr><th scope="col">Waktu</th><th scope="col">Pelaku</th><th scope="col">Aksi</th><th scope="col">Objek</th><th scope="col">Nilai lama</th><th scope="col">Nilai baru</th></tr></thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="text-nowrap small">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->user->nama }}</td>
                        <td><code>{{ $log->aksi }}</code></td>
                        <td class="small">{{ $log->objek }} #{{ $log->objek_id }}</td>
                        <td><pre class="small mb-0 kk-json">{{ $log->nilai_lama ? json_encode($log->nilai_lama, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '-' }}</pre></td>
                        <td><pre class="small mb-0 kk-json">{{ $log->nilai_baru ? json_encode($log->nilai_baru, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : '-' }}</pre></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-body-secondary">Belum ada log.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $logs->links() }}
@endsection
