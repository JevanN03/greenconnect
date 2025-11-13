@extends('layouts.app')
@php($suppressFlash = true)
@section('content')
<h3 class="mb-3">Kelola Artikel</h3>

<form class="row g-2 mb-3" method="GET" action="{{ route('admin.articles.index') }}">
  <div class="col-auto">
    <input type="text" name="q" class="form-control" placeholder="Cari judul..." value="{{ request('q') }}">
  </div>
  <div class="col-auto">
    <button class="btn btn-outline-secondary">Cari</button>
  </div>
  <div class="col-auto ms-auto">
    <a class="btn btn-success" href="{{ route('admin.articles.create') }}">Tambah</a>
  </div>
</form>

<table class="table align-middle">
  <thead>
    <tr>
      <th>Judul</th>
      <th>Dibuat</th>
      <th class="text-start th-actions">Aksi</th>
    </tr>
  </thead>
  <tbody>
  @foreach($articles as $a)
    <tr>
      <td>{{ $a->title }}</td>
      <td>{{ $a->created_at->format('d M Y') }}</td>
      <td class="td-actions">
        <div class="d-inline-flex align-items-center gap-2">
          <a href="{{ route('admin.articles.edit', $a) }}"
            class="btn btn-primary">Edit</a>
          <form method="POST" action="{{ route('admin.articles.destroy', $a) }}">
            @csrf @method('DELETE')
            <button type="submit"
                    class="btn btn-danger js-confirm-delete"
                    data-title="Hapus artikel?"
                    data-text="Artikel akan dihapus permanen."
                    data-confirm="Ya, hapus">
              Hapus
            </button>
          </form>
        </div>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $articles->links() }}
@endsection

@push('scripts')
  <script>
    @if(session('success')) flashToast('success', @json(session('success'))); @endif
    @if(session('error'))   flashToast('error',   @json(session('error')));   @endif
  </script>
@endpush
