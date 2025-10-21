@extends('layouts.app')
@section('content')
  <h3 class="mb-3">Kelola Diskusi</h3>

  {{-- Form Pencarian --}}
  <form class="row g-2 mb-3" method="GET" action="{{ route('admin.discussions.index') }}">
    <div class="col-sm-8 col-md-6">
      <input type="text" name="q" class="form-control" placeholder="Cari diskusi (judul/isi)…" value="{{ request('q') }}">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
    @if(request('q'))
      <div class="col-auto">
        <a class="btn btn-outline-danger" href="{{ route('admin.discussions.index') }}">Reset</a>
      </div>
    @endif
  </form>

  @if($discussions->count())
    @foreach($discussions as $d)
      <div class="mb-4 p-3 border rounded">
        <div class="d-flex justify-content-between align-items-start">
          <div>
            <h5 class="mb-1">{{ $d->title }}</h5>
            <p class="small text-muted mb-2">
              oleh {{ $d->user->name }} • {{ $d->created_at->format('d M Y H:i') }}
            </p>
          </div>

          {{-- Hapus Diskusi --}}
          <form method="POST" action="{{ route('admin.discussions.destroy', $d) }}" onsubmit="return confirm('Hapus diskusi beserta semua balasannya?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Hapus</button>
          </form>
        </div>

        <p class="mb-3">{{ $d->body }}</p>

        {{-- Balasan --}}
        <div class="mb-2"><strong>Balasan ({{ $d->replies->count() }}):</strong></div>
        @forelse($d->replies as $r)
          <div class="ps-3 border-start ms-2 mb-2">
            <p class="mb-1">
              <strong>{{ $r->user->name }}</strong>
              <span class="text-muted small">• {{ $r->created_at->diffForHumans() }}</span>
            </p>
            <p class="mb-0">{{ $r->body }}</p>
          </div>
        @empty
          <p class="text-muted small ms-2">Belum ada balasan.</p>
        @endforelse

        {{-- Balas sebagai Admin --}}
        <form method="POST" action="{{ route('admin.discussions.reply', $d) }}" class="mt-3">
          @csrf
          <div class="input-group">
            <input type="text" name="body" class="form-control" placeholder="Balas sebagai Admin…" required>
            <button class="btn btn-success">Kirim</button>
          </div>
        </form>
      </div>
    @endforeach

    <div class="mt-3">
      {{ $discussions->links() }}
    </div>
  @else
    <div class="alert alert-info">Belum ada diskusi.</div>
  @endif
@endsection
