@extends('layouts.app')
@section('content')
<h3 class="mb-3">Tambah TPA/TPS</h3>
<form method="POST" action="{{ route('collection-points.store') }}" class="border p-3 rounded">
  @csrf
  <div class="mb-3"><label class="form-label">Nama</label><input name="name" class="form-control" required></div>
  <div class="mb-3"><label class="form-label">Kontak</label><input name="contact" class="form-control"></div>
  <div class="mb-3"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="3" required></textarea></div>
  <div class="mb-3"><label class="form-label">Link Peta</label><input name="map_link" class="form-control" placeholder="https://..."></div>
  <button class="btn btn-success">Simpan</button>
</form>
@endsection
