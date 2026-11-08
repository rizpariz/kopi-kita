@extends('layouts.app')

@section('title', 'Profil')

@section('content')
    <h1 class="h4 mb-4">Profil</h1>
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="h5 mb-3">Data akun</h2>
                    <form method="POST" action="{{ route('profile.update') }}" novalidate>
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" value="{{ $user->email }}" class="form-control" readonly aria-describedby="email-help">
                            <div class="form-text" id="email-help">Email tidak dapat diubah.</div>
                        </div>
                        @foreach ([['nama', 'Nama lengkap', 'text'], ['no_hp', 'Nomor HP', 'tel']] as [$field, $label, $type])
                            <div class="mb-3">
                                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                <input type="{{ $type }}" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $user->{$field}) }}" required
                                    class="form-control @error($field) is-invalid @enderror" @error($field) aria-describedby="{{ $field }}-error" @enderror>
                                @error($field)
                                    <div class="invalid-feedback" id="{{ $field }}-error">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="h5 mb-3">Ubah password</h2>
                    <form method="POST" action="{{ route('profile.password') }}" novalidate>
                        @csrf
                        @method('PUT')
                        @foreach ([['password_lama', 'Password lama', 'current-password'], ['password', 'Password baru', 'new-password'], ['password_confirmation', 'Ulangi password baru', 'new-password']] as [$field, $label, $autocomplete])
                            <div class="mb-3">
                                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                                <input type="password" id="{{ $field }}" name="{{ $field }}" required autocomplete="{{ $autocomplete }}"
                                    class="form-control @error($field) is-invalid @enderror" @error($field) aria-describedby="{{ $field }}-error" @enderror>
                                @error($field)
                                    <div class="invalid-feedback" id="{{ $field }}-error">{{ $message }}</div>
                                @enderror
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-outline-primary">Ubah password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
