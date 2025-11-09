@extends('layouts.app')

@section('content')
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">Lokasi TPA/TPS</h2>
  </div>

  {{-- Pencarian sederhana --}}
  <form class="row g-2 mb-3" method="GET" action="{{ route('collection-points.index') }}">
    <div class="col-sm-8 col-md-6">
      <input type="text" name="q" class="form-control" placeholder="Cari nama/alamat..."
            value="{{ request('q') }}">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
    @if(request('q'))
      <div class="col-auto">
        <a class="btn btn-outline-danger" href="{{ route('collection-points.index') }}">Reset</a>
      </div>
    @endif
  </form>

  @forelse($points as $p)
    <div class="card mb-3 shadow-sm">
      <div class="row g-0">
        <div class="col-12 col-lg-7">
          <div class="ratio ratio-16x9 rounded-start rounded-top">
            @if($p->map_embed_url)
              <iframe
                src="{{ $p->map_embed_url }}"
                style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen></iframe>
            @else
              <div class="d-flex align-items-center justify-content-center bg-light text-muted">
                <small>Tidak ada peta</small>
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-lg-5">
          <div class="card-body h-100 d-flex flex-column">
            <h5 class="card-title mb-1">{{ $p->name }}</h5>
            <p class="mb-2 text-muted small">
              <i class="bi bi-geo-alt"></i> {{ $p->address }}
            </p>
            @if($p->contact)
              <p class="mb-3"><i class="bi bi-telephone"></i> {{ $p->contact }}</p>
            @endif
            <div class="mt-auto d-flex gap-2">
              @if($p->map_url)
                <a href="{{ $p->map_url }}" target="_blank" rel="noopener" class="btn btn-success btn-sm">
                  Buka di Google Maps
                </a>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  @empty
    <div class="alert alert-info">Belum ada data TPA/TPS.</div>
  @endforelse

  <div class="mt-3">
    {{ $points->links() }}
  </div>
@endsection
