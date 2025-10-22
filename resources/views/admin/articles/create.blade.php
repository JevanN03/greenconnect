@extends('layouts.app')
@section('content')
<h3 class="mb-3">Tambah Artikel</h3>

<form method="POST" action="{{ route('admin.articles.store') }}" class="border p-3 rounded">
  @csrf
  <div class="mb-3">
    <label class="form-label">Judul</label>
    <input name="title" class="form-control" value="{{ old('title') }}" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Ringkasan</label>
    <textarea name="excerpt" class="form-control" rows="2">{{ old('excerpt') }}</textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Konten</label>
    <textarea name="content" class="form-control" rows="6" required>{{ old('content') }}</textarea>
  </div>

  <button class="btn btn-success">Simpan</button>
  <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Batal</a>
</form>
@endsection
