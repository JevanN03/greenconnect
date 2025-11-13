@extends('layouts.app')

@php($suppressFlash = true)

@section('content')
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h3 class="mb-0">Kelola Laporan</h3>
  </div>

  {{-- Pencarian --}}
  <form class="row g-2 mb-3" method="GET" action="{{ route('admin.reports.index') }}">
    <div class="col-sm-8 col-md-6">
      <input type="text" name="q" class="form-control" placeholder="Cari judul/deskripsi/status..."
            value="{{ request('q') }}">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
    @if(request('q'))
      <div class="col-auto">
        <a class="btn btn-outline-danger" href="{{ route('admin.reports.index') }}">Reset</a>
      </div>
    @endif
  </form>

  <div class="table-responsive">
    <table class="table align-middle">
      <thead>
        <tr>
          <th style="width:80px">ID</th>
          <th>Judul</th>
          <th>Pelapor</th>
          <th>Status</th>
          <th>Tanggal</th>
          <th class="text-start th-actions">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reports as $report)
          <tr>
            <td>#{{ $report->id }}</td>
            <td>{{ \Illuminate\Support\Str::limit($report->title ?? $report->subject, 60) }}</td>
            <td>{{ $report->user->name ?? '—' }}</td>
            <td>
              <span class="badge text-bg-{{ $report->status === 'closed' ? 'success' : ($report->status === 'in_progress' ? 'warning' : 'secondary') }}">
                {{ ucfirst(str_replace('_',' ', $report->status)) }}
              </span>
            </td>
            <td>{{ $report->created_at->format('d M Y') }}</td>
            <td class="td-actions">
              <div class="d-inline-flex align-items-center gap-2">
                <a href="{{ route('admin.reports.edit', $report) }}"
                  class="btn btn-primary">Edit</a>
                <form method="POST" action="{{ route('admin.reports.destroy', $report) }}">
                  @csrf @method('DELETE')
                  <button type="submit"
                          class="btn btn-danger js-confirm-delete"
                          data-title="Hapus laporan?"
                          data-text="Tindakan ini tidak bisa dibatalkan."
                          data-confirm="Ya, hapus">
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">Belum ada data.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $reports->links() }}
  </div>
@endsection

@push('scripts')
  <script>
    @if(session('success')) flashToast('success', @json(session('success'))); @endif
    @if(session('error'))   flashToast('error',   @json(session('error')));   @endif
  </script>
@endpush
