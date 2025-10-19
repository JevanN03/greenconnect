@extends('layouts.app')
@section('content')
<h3 class="mb-3">Kelola Artikel</h3>

<form class="row g-2 mb-3">
  <div class="col-auto">
    <input type="text" name="q" class="form-control" placeholder="Cari judul..." value="{{ request('q') }}">
  </div>
  <div class="col-auto">
    <button class="btn btn-outline-secondary">Cari</button>
  </div>
  <div class="col-auto ms-auto">
    <a class="btn btn-success" href="{{ route('articles.create') }}">Tambah</a>
  </div>
</form>

<table class="table table-hover">
  <thead><tr><th>Judul</th><th>Dibuat</th><th>Aksi</th></tr></thead>
  <tbody>
  @foreach($articles as $a)
    <tr>
      <td>{{ $a->title }}</td>
      <td>{{ $a->created_at->format('d M Y') }}</td>
      <td>
        <a class="btn btn-sm btn-primary" href="{{ route('articles.edit',$a) }}">Edit</a>
        <form action="{{ route('articles.destroy',$a) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus artikel?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Hapus</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $articles->links() }}
@endsection
