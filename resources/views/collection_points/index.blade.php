@extends('layouts.app')

@section('content')
  <h2 class="mb-3">Daftar TPA/TPS</h2>

  {{-- Form Pencarian --}}
  <form class="row g-2 mb-3" method="GET" action="{{ route('collection-points.index') }}">
    <div class="col-sm-8 col-md-6">
      <input
        type="text"
        name="q"
        class="form-control"
        placeholder="Cari nama atau alamat..."
        value="{{ old('q', $q ?? request('q')) }}"
      >
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
    @if(($q ?? request('q')))
      <div class="col-auto">
        <a class="btn btn-outline-danger" href="{{ route('collection-points.index') }}">Reset</a>
      </div>
    @endif
  </form>

  @if($points->count())
    <div class="table-responsive">
      <table class="table table-striped align-middle">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Kontak</th>
            <th>Alamat</th>
            <th>Peta</th>
          </tr>
        </thead>
        <tbody>
          @foreach($points as $p)
            <tr>
              <td>{{ $p->name }}</td>
              <td>{{ $p->contact ?: '-' }}</td>
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
    </div>

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $points->links() }}
    </div>
  @else
    <div class="alert alert-info">
      @if(($q ?? request('q')))
        Tidak ada TPA/TPS yang cocok dengan pencarian
        <strong>"{{ $q ?? request('q') }}"</strong>.
        <a href="{{ route('collection-points.index') }}" class="alert-link">Tampilkan semua</a>.
      @else
        Belum ada data TPA/TPS.
      @endif
    </div>
  @endif
@endsection
