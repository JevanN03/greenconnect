@extends('layouts.app')
@section('content')
<h3 class="mb-3">Edit TPA/TPS</h3>
<form method="POST" action="{{ route('collection-points.update',$point) }}" class="border p-3 rounded">
  @csrf @method('PUT')
  <div class="mb-3"><label class="form-label">Nama</label><input name="name" class="form-control" value="{{ old('name',$point->name) }}" required></div>
  <div class="mb-3"><label class="form-label">Kontak</label><input name="contact" class="form-control" value="{{ old('contact',$point->contact) }}"></div>
  <div class="mb-3"><label class="form-label">Alamat</label><textarea name="address" class="form-control" rows="3" required>{{ old('address',$point->address) }}</textarea></div>
  <div class="mb-3"><label class="form-label">Link Peta</label><input name="map_link" class="form-control" value="{{ old('map_link',$point->map_link) }}"></div>
  <button class="btn btn-success">Simpan</button>
</form>
@endsection
