<!doctype html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') | @endif{{ config('app.name') }}</title>
    @include('partials.assets-css')
    @stack('styles')
</head>
{{-- kk-theme: tema majalah editorial untuk semua halaman (lihat bagian TEMA di public/css/app.css). --}}
<body class="kk-theme @yield('body_class')">
    @yield('body')

    <div class="toast-container position-fixed bottom-0 end-0 p-3" id="toast-container" aria-live="polite" aria-atomic="true"></div>

    @include('partials.assets-js')
    @stack('scripts')
</body>
</html>
