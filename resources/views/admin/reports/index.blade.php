@extends('layouts.app')
@section('content')
<h3 class="mb-3">Kelola Laporan</h3>
<table class="table table-hover">
  <thead><tr><th>Waktu</th><th>Pelapor</th><th>Judul</th><th>Status</th><th>Aksi</th></tr></thead>
  <tbody>
  @foreach($reports as $rep)
    <tr>
      <td>{{ $rep->created_at->format('d M Y H:i') }}</td>
      <td>{{ $rep->user->name }}</td>
      <td>{{ $rep->title }}</td>
      <td>{{ ucfirst($rep->status) }}</td>
      <td>
        <a class="btn btn-sm btn-primary" href="{{ route('admin.reports.edit',$rep) }}">Edit</a>
        <form action="{{ route('admin.reports.destroy',$rep) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Hapus</button>
        </form>
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $reports->links() }}
@endsection
