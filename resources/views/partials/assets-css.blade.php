@if (config('services.asset_cdn'))
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    {{-- Huruf tema editorial: Anton (judul raksasa), Instrument Serif (aksen miring), IBM Plex Mono (label),
         Plus Jakarta Sans (teks). Tanpa internet, CSS memakai huruf cadangan. --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anton&family=IBM+Plex+Mono:wght@400;500;600&family=Instrument+Serif:ital@0;1&family=Plus+Jakarta+Sans:wght@400..800&display=swap">
@else
    <link rel="stylesheet" href="{{ asset('vendor/bootstrap/bootstrap.min.css') }}">
@endif
{{-- ?v= berubah setiap kali app.css disimpan, jadi browser tidak memakai versi lama dari cache. --}}
<link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
