<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name','GreenConnect') }}</title>

  <!-- Bootstrap CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  />

  <!-- Bootstrap Icons (untuk ikon kecil di beberapa section) -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
    rel="stylesheet"
  />

  <!-- Vite -->
  @vite(['resources/js/app.js'])

  @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
      @php
            $brandUrl = route('landing');                   // default untuk guest
            if (auth()->check()) {
                $brandUrl = auth()->user()->is_admin
                    ? route('admin.articles.index')         // admin -> Kelola Artikel
                    : route('home');                        // user biasa -> beranda user
            }
        @endphp
        <a class="navbar-brand fw-bold" href="{{ $brandUrl }}">GreenConnect</a>




      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        {{-- ================= MENU KIRI ================= --}}
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          @if(auth()->check() && auth()->user()->is_admin)
            {{-- ===== NAVBAR ADMIN ===== --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}"
                href="{{ route('admin.articles.index') }}">Kelola Artikel</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.collection-points.*') ? 'active' : '' }}"
                href="{{ route('admin.collection-points.index') }}">Kelola TPA/TPS</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.discussions.*') ? 'active' : '' }}"
                href="{{ route('admin.discussions.index') }}">Kelola Diskusi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}"
                href="{{ route('admin.reports') }}">Kelola Laporan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                href="{{ route('admin.dashboard') }}">Lihat Grafik</a>
            </li>
          @else
            {{-- ===== NAVBAR PUBLIK / USER ===== --}}
            <li class="nav-item">
              <a
                class="nav-link {{ request()->routeIs('landing') || request()->routeIs('home') ? 'active' : '' }}"
                href="{{ auth()->check() ? route('home') : route('landing') }}"
              >Beranda</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}"
                href="{{ route('articles.index') }}">Artikel</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('collection-points.*') ? 'active' : '' }}"
                href="{{ route('collection-points.index') }}">TPA/TPS</a>
            </li>
            {{-- Tampilkan link Diskusi & Pelaporan juga ke guest; akan diarahkan ke /login oleh middleware auth --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('discussions.*') ? 'active' : '' }}"
                href="{{ route('discussions.index') }}">Diskusi</a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('reports.create') ? 'active' : '' }}"
                href="{{ route('reports.create') }}">Pelaporan</a>
            </li>
            @auth
              <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('reports.check') ? 'active' : '' }}"
                  href="{{ route('reports.check') }}">Cek Laporan</a>
              </li>
            @endauth
          @endif
        </ul>

        {{-- ================= MENU KANAN (BUTTONS) ================= --}}
        <div class="ms-auto d-flex align-items-center gap-2">
          @guest
            <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm" aria-label="Masuk">
              Masuk
            </a>
            <a href="{{ route('register') }}" class="btn btn-light btn-sm text-success fw-semibold" aria-label="Daftar">
              Daftar
            </a>
          @else
            <span class="navbar-text me-2 d-none d-md-inline">
              Halo, {{ auth()->user()->is_admin ? 'Admin' : auth()->user()->name }}
            </span>
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
              @csrf
              <button class="btn btn-outline-light btn-sm" aria-label="Logout">Logout</button>
            </form>
          @endguest
        </div>
      </div>
    </div>
  </nav>

  {{-- ================= KONTEN UTAMA ================= --}}
<main class="py-4">
  <div class="container">

    {{-- Flash global (bisa dipadamkan dari view dengan $suppressFlash = true) --}}
    @unless(isset($suppressFlash) && $suppressFlash)
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
      @endif
    @endunless

    @yield('content')
  </div>
</main>

  {{-- ================= FOOTER (sticky, tidak menutupi konten) ================= --}}
  <footer class="bg-light border-top py-3">
    <div class="container d-flex flex-wrap justify-content-between align-items-center small">
      <div class="text-muted">
        &copy; {{ date('Y') }} GreenConnect — Edukasi & Aksi Pengelolaan Sampah
      </div>
      <ul class="nav">
        <li class="nav-item"><a class="nav-link px-2" href="#">Tentang</a></li>
        <li class="nav-item"><a class="nav-link px-2" href="#">Kontak</a></li>
        <li class="nav-item"><a class="nav-link px-2" href="#">Kebijakan Privasi</a></li>
        <li class="nav-item"><a class="nav-link px-2" href="#">Syarat Layanan</a></li>
        <li class="nav-item"><a class="nav-link px-2" href="#">Media Sosial</a></li>
      </ul>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
  ></script>

  @stack('scripts')
</body>
</html>
