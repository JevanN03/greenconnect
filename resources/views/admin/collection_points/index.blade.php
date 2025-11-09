@extends('layouts.app')

{{-- Matikan flash global dari layout --}}
@php($suppressFlash = true)

@section('content')
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Kelola TPA/TPS</h3>
    <a href="{{ route('admin.collection-points.create') }}" class="btn btn-success">Tambah</a>
  </div>

  {{-- Flash KHUSUS halaman ini (ditaruh di bawah H1 + tombol Tambah) --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- Pencarian --}}
  <form class="row g-2 mb-3" method="GET" action="{{ route('admin.collection-points.index') }}">
    <div class="col-sm-8 col-md-6">
      <input type="text" name="q" class="form-control" placeholder="Cari nama/alamat..."
            value="{{ request('q') }}">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
    @if(request('q'))
      <div class="col-auto">
        <a class="btn btn-outline-danger" href="{{ route('admin.collection-points.index') }}">Reset</a>
      </div>
    @endif
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th style="width:220px">Preview</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th>Kontak</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($points as $p)
          <tr>
            <td>
              <div class="ratio ratio-16x9" style="max-width:220px">
                @if($p->map_embed_url)
                  <iframe src="{{ $p->map_embed_url }}" style="border:0" loading="lazy"></iframe>
                @else
                  <div class="bg-light d-flex align-items-center justify-content-center text-muted small rounded">
                    Tidak ada peta
                  </div>
                @endif
              </div>
            </td>
            <td class="fw-semibold">{{ $p->name }}</td>
            <td>{{ \Illuminate\Support\Str::limit($p->address, 80) }}</td>
            <td>{{ $p->contact }}</td>
            <td class="text-end">
              <div class="d-inline-flex gap-2 justify-content-end flex-wrap">
                <a href="{{ route('admin.collection-points.edit', $p) }}"
                   class="btn btn-primary rounded-3 px-4"
                   style="min-width:110px">
                  Edit
                </a>
                <form method="POST" action="{{ route('admin.collection-points.destroy', $p) }}"
                      class="d-inline" onsubmit="return confirm('Hapus data ini?')">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="btn btn-danger rounded-3 px-4"
                          style="min-width:110px">
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">Belum ada data.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $points->links() }}
  </div>
@endsection
