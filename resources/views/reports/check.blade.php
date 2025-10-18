@extends('layouts.app')
@section('content')
<h2 class="mb-3">Status Laporan Saya</h2>
<table class="table align-middle">
  <thead><tr><th>Waktu</th><th>Judul</th><th>Status</th><th>Bukti</th></tr></thead>
  <tbody>
  @foreach($myReports as $rep)
    <tr>
      <td>{{ $rep->created_at->format('d M Y H:i') }}</td>
      <td>{{ $rep->title }}</td>
      <td>
        <span class="badge text-bg-{{ $rep->status === 'baru' ? 'secondary' : ($rep->status==='diproses'?'warning':'success') }}">
          {{ ucfirst($rep->status) }}
        </span>
      </td>
      <td>
        @if($rep->photo_path)
          <a href="{{ asset('storage/'.$rep->photo_path) }}" target="_blank">Lihat</a>
        @else -
        @endif
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
{{ $myReports->links() }}
@endsection
