@extends('layouts.app')
@section('content')
<h2 class="mb-3">Buat Laporan Sampah</h2>
<form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="border p-3 rounded">
  @csrf
  <div class="mb-3">
    <label class="form-label">Judul</label>
    <input type="text" name="title" class="form-control" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control" rows="4" required></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Foto (opsional)</label>
    <input type="file" name="photo" class="form-control" accept="image/*">
  </div>
  <button class="btn btn-success">Kirim Laporan</button>
</form>
@endsection
