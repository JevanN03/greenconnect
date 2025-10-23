@extends('layouts.app')

@section('content')
  {{-- 1) HERO --}}
  <section class="p-4 p-md-5 bg-light rounded-3 border mb-4">
    <div class="row align-items-center gy-3">
      <div class="col-lg-8">
        <h1 class="display-6 fw-bold mb-2">
          Bersama kurangi sampah.
        </h1>
        <p class="fs-5 mb-3">
          Temukan cara kelola sampah yang benar dan laporkan titik penumpukan di sekitar Anda.
        </p>

        <div class="d-flex gap-2 flex-wrap">
          <a href="{{ route('articles.index') }}" class="btn btn-success btn-lg">
            Baca Edukasi
          </a>

          {{-- Guest yang klik "Lapor Sekarang" akan diarahkan ke /login (route dilindungi 'auth') --}}
          <a href="{{ route('reports.create') }}" class="btn btn-outline-success btn-lg">
            Lapor Sekarang
          </a>
        </div>
      </div>

      <div class="col-lg-4 text-center">
        {{-- Ilustrasi kecil pakai ikon-ikon Bootstrap --}}
        <div class="display-1"><i class="bi bi-recycle"></i></div>
        <div class="text-muted">Edukasi • Pelaporan • Tindak Lanjut</div>
      </div>
    </div>
  </section>

  {{-- 2) CARA KERJA --}}
  <section class="mb-4">
    <h2 class="h4 fw-bold mb-3">Cara Kerja</h2>

    <div class="row g-3">
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="display-6 mb-2 text-success"><i class="bi bi-book-half"></i></div>
            <h5 class="card-title">Pelajari</h5>
            <p class="card-text mb-0">
              Baca artikel mengenai pengelolaan sampah yang benar.
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="display-6 mb-2 text-success"><i class="bi bi-camera"></i></div>
            <h5 class="card-title">Lapor</h5>
            <p class="card-text mb-0">
              Isi form, unggah foto, sertakan titik lokasi penumpukan sampah.
            </p>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <div class="display-6 mb-2 text-success"><i class="bi bi-clipboard-check"></i></div>
            <h5 class="card-title">Tindak Lanjut</h5>
            <p class="card-text mb-0">
              Pantau status laporan Anda hingga selesai ditangani.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- 3) RINGKASAN STATISTIK --}}
  <section class="mb-4">
    <h2 class="h4 fw-bold mb-3">Ringkasan Statistik</h2>

    <div class="row g-3">
      <div class="col-12 col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="small text-muted">Total artikel dipublikasi</div>
            <div class="display-6 fw-bold">{{ number_format($stats['articles_total'] ?? 0) }}</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="small text-muted">Laporan masuk bulan ini</div>
            <div class="display-6 fw-bold">{{ number_format($stats['reports_month'] ?? 0) }}</div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="small text-muted">Laporan selesai/ditangani</div>
            <div class="display-6 fw-bold">{{ number_format($stats['reports_done'] ?? 0) }}</div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- 4) FAQ MINI --}}
  <section class="mb-4">
    <h2 class="h4 fw-bold mb-3">FAQ</h2>

    <div class="accordion" id="faqMini">
      <div class="accordion-item">
        <h2 class="accordion-header" id="q1">
          <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#a1">
            Apakah laporan saya anonim?
          </button>
        </h2>
        <div id="a1" class="accordion-collapse collapse show" data-bs-parent="#faqMini">
          <div class="accordion-body">
            Data akun digunakan untuk verifikasi & tindak lanjut. Informasi publik tidak menampilkan data sensitif Anda.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="q2">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a2">
            Berapa lama proses penanganan?
          </button>
        </h2>
        <div id="a2" class="accordion-collapse collapse" data-bs-parent="#faqMini">
          <div class="accordion-body">
            Waktu bervariasi. Anda dapat memantau status pada menu <em>Cek Laporan</em>.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="q3">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a3">
            Jenis foto yang dibutuhkan?
          </button>
        </h2>
        <div id="a3" class="accordion-collapse collapse" data-bs-parent="#faqMini">
          <div class="accordion-body">
            Foto jelas area penumpukan, usahakan dari sudut yang memperlihatkan skala. Maksimal 2MB.
          </div>
        </div>
      </div>

      <div class="accordion-item">
        <h2 class="accordion-header" id="q4">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#a4">
            Apakah bisa tanpa login?
          </button>
        </h2>
        <div id="a4" class="accordion-collapse collapse" data-bs-parent="#faqMini">
          <div class="accordion-body">
            Melihat artikel & TPA/TPS bisa tanpa login. Untuk Diskusi & Pelaporan, Anda perlu login terlebih dahulu.
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
