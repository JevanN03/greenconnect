@extends('layouts.app')
@section('content')
<h3 class="mb-3">Edit Laporan</h3>
<form method="POST" action="{{ route('admin.reports.update',$report) }}" 
      class="js-confirm-save" data-title="Simpan perubahan?" data-text="Status & detail laporan akan diperbarui.">
  @csrf @method('PUT')
  <div class="mb-3">
    <label class="form-label">Judul</label>
    <input type="text" name="title" class="form-control" value="{{ old('title',$report->title) }}" required>
  </div>
  <div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control" rows="4" required>{{ old('description',$report->description) }}</textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Status</label>
    <select name="status" class="form-select" required>
      @foreach(['baru','diproses','selesai'] as $s)
        <option value="{{ $s }}" @selected($report->status===$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
  </div>
  <button class="btn btn-success">Simpan</button>
</form>
@endsection
