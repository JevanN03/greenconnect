@extends('layouts.app')
@php($suppressFlash = true)

@section('content')
@push('styles')
<style>
  .btn-admin-col{
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    gap:.5rem;
  }
</style>
@endpush

  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Kelola TPA/TPS</h3>
  </div>

{{-- Pencarian --}}
  <form class="row g-2 align-items-center mb-3" method="GET"
        action="{{ route('admin.collection-points.index') }}">
    <div class="col-12 col-md-6">
      <input type="text" name="q" class="form-control"
            placeholder="Cari nama/alamat..." value="{{ request('q') }}">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
    @if(request('q'))
      <div class="col-auto">
        <a class="btn btn-outline-danger" href="{{ route('admin.collection-points.index') }}">Reset</a>
      </div>
    @endif
    <div class="col-auto ms-auto">
      <a href="{{ route('admin.collection-points.create') }}" class="btn btn-success">Tambah</a>
    </div>
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th style="width:220px">Preview</th>
          <th>Nama</th>
          <th>Alamat</th>
          <th>Kontak</th>
          <th class="text-end th-actions pe-0">Aksi</th>
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
            <td class="td-actions text-end pe-0">
              <div class="btn-admin-col">
                <a href="{{ route('admin.collection-points.edit', $p) }}"
                  class="btn btn-primary">Edit</a>
                <form method="POST" action="{{ route('admin.collection-points.destroy', $p) }}">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="btn btn-danger js-confirm-delete"
                          data-title="Hapus TPA/TPS?"
                          data-text="Data lokasi akan dihapus."
                          data-confirm="Ya, hapus">
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

@push('scripts')
  <script>
    @if(session('success')) flashToast('success', @json(session('success'))); @endif
    @if(session('error'))   flashToast('error',   @json(session('error')));   @endif
  </script>
@endpush
