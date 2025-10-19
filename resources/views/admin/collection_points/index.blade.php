@extends('layouts.app')
@section('content')
<h3 class="mb-3">Kelola TPA/TPS</h3>

<form class="row g-2 mb-3">
  <div class="col-auto">
    <input type="text" name="q" class="form-control" placeholder="Cari nama/alamat..." value="{{ request('q') }}">
  </div>
  <div class="col-auto">
    <button class="btn btn-outline-secondary">Cari</button>
  </div>
  <div class="col-auto ms-auto">
    <a class="btn btn-success" href="{{ route('collection-points.create') }}">Tambah</a>
  </div>
</form>

<table class="table table-striped">
  <thead><tr><th>Nama</th><th>Kontak</th><th>Alamat</th><th>Peta</th><th>Aksi</th></tr></thead>
  <tbody>
  @foreach($points as $p)
    <tr>
      <td>{{ $p->name }}</td>
      <td>{{ $p->contact }}</td>
      <td>{{ $p->address }}</td>
      <td>@if($p->map_link)<a href="{{ $p->map_link }}" target="_blank">Lihat</a>@else - @endif</td>
      <td>
        <a class="btn btn-sm btn-primary" href="{{ route('collection-points.edit',$p) }}">Edit</a>
        <form action="{{ route('collection-points.destroy',$p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Hapus</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $points->links() }}
@endsection
