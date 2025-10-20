<!doctype html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name','GreenConnect') }}</title>

  <!-- Bootstrap CSS via CDN -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  >

  <!-- Vite (JS/CSS app) -->
  @vite(['resources/js/app.js'])
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-success">
    <div class="container">
      <a class="navbar-brand fw-bold" href="{{ route('landing') }}">GreenConnect</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="mainNav">
        {{-- ========== MENU KIRI ========== --}}
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
              >
                Beranda
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('articles.*') ? 'active' : '' }}"
                href="{{ route('articles.index') }}">Artikel</a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('collection-points.*') ? 'active' : '' }}"
                href="{{ route('collection-points.index') }}">TPA/TPS</a>
            </li>

            {{-- Link Diskusi & Pelaporan selalu terlihat.
                Jika guest menekan, akan diarahkan ke /login (karena route dilindungi auth). --}}
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

        {{-- ========== MENU KANAN (AUTH) ========== --}}
        <ul class="navbar-nav ms-auto">
          @guest
            <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Masuk</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Daftar</a></li>
          @else
            <li class="nav-item">
              <span class="navbar-text me-3">
                Halo, {{ auth()->user()->is_admin ? 'Admin' : auth()->user()->name }}
              </span>
            </li>
            <li class="nav-item">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">Logout</button>
              </form>
            </li>
          @endguest
        </ul>
      </div>
    </div>
  </nav>

  <main class="py-4">
    <div class="container">
      @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $e)
              <li>{{ $e }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      @yield('content')
    </div>
  </main>

  <footer class="bg-light border-top py-3 mt-5">
    <div class="container text-center small text-muted">
      &copy; {{ date('Y') }} GreenConnect — Edukasi & Aksi Pengelolaan Sampah
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
          integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
          crossorigin="anonymous"></script>

  @stack('scripts')
</body>
</html>
