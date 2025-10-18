@extends('layouts.app')

@section('content')
<div class="p-4 p-md-5 mb-4 bg-light rounded-3 border">
  <div class="container-fluid py-5">
    <h1 class="display-6 fw-bold">Selamat datang di GreenConnect</h1>
    <p class="col-md-8 fs-5">
      Edukasi & aksi pengelolaan sampah. Mari ikut berkontribusi menjaga lingkungan!
    </p>
    @guest
      <a href="{{ route('login') }}" class="btn btn-success btn-lg">Masuk untuk Berpartisipasi</a>
    @else
      <a href="{{ route('discussions.index') }}" class="btn btn-success">Mulai Diskusi</a>
      <a href="{{ route('reports.create') }}" class="btn btn-outline-success">Buat Laporan</a>
    @endguest
  </div>
</div>
@endsection
