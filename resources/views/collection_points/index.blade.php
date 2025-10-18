@extends('layouts.app')
@section('content')
<h2 class="mb-3">Daftar TPA/TPS</h2>
<table class="table table-striped">
  <thead><tr><th>Nama</th><th>Kontak</th><th>Alamat</th><th>Peta</th></tr></thead>
  <tbody>
    @foreach($points as $p)
      <tr>
        <td>{{ $p->name }}</td>
        <td>{{ $p->contact ?? '-' }}</td>
        <td>{{ $p->address }}</td>
        <td>
          @if($p->map_link)
            <a href="{{ $p->map_link }}" target="_blank" rel="noopener">Lihat</a>
          @else
            -
          @endif
        </td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection
