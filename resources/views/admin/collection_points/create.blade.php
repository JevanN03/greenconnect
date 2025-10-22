@extends('layouts.app')
@section('content')
<h3 class="mb-3">Tambah TPA/TPS</h3>

<form method="POST" action="{{ route('admin.collection-points.store') }}" class="border p-3 rounded">
  @csrf
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input name="name" class="form-control" value="{{ old('name') }}" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Kontak</label>
    <input name="contact" class="form-control" value="{{ old('contact') }}">
  </div>

  <div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="address" class="form-control" rows="3" required>{{ old('address') }}</textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Link Peta</label>
    <input name="map_link" class="form-control" placeholder="https://..." value="{{ old('map_link') }}">
  </div>

  <button class="btn btn-success">Simpan</button>
  <a href="{{ route('admin.collection-points.index') }}" class="btn btn-outline-secondary">Batal</a>
</form>
@endsection
