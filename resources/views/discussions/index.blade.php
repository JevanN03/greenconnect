@extends('layouts.app')

@section('content')
  <h2 class="mb-3">Diskusi</h2>

  {{-- Form Pencarian Diskusi --}}
  <form class="row g-2 mb-3" method="GET" action="{{ route('discussions.index') }}">
    <div class="col-sm-8 col-md-6">
      <input
        type="text"
        name="q"
        class="form-control"
        placeholder="Cari topik..."
        value="{{ old('q', $q ?? request('q')) }}"
      >
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
    @if(($q ?? request('q')))
      <div class="col-auto">
        <a class="btn btn-outline-danger" href="{{ route('discussions.index') }}">Reset</a>
      </div>
    @endif
  </form>

  {{-- Form Buat Diskusi Baru --}}
  <form method="POST" action="{{ route('discussions.store') }}" class="mb-4 border rounded p-3">
    @csrf
    <div class="mb-2">
      <label class="form-label">Judul</label>
      <input type="text" name="title" class="form-control" placeholder="Judul diskusi" required>
    </div>
    <div class="mb-2">
      <label class="form-label">Isi</label>
      <textarea name="body" class="form-control" rows="3" placeholder="Tulis topik diskusi..." required></textarea>
    </div>
    <button class="btn btn-success">Kirim</button>
  </form>

  @if($discussions->count())
    @foreach($discussions as $d)
      <div class="mb-4 p-3 border rounded">
        <h5 class="mb-1">{{ $d->title }}</h5>
        <p class="small text-muted mb-2">
          oleh {{ $d->user->name }} • {{ $d->created_at->diffForHumans() }}
        </p>
        <p class="mb-3">{{ $d->body }}</p>

        {{-- Form balas (flat reply) --}}
        <form method="POST" action="{{ route('discussions.reply', $d) }}" class="mb-2">
          @csrf
          <div class="input-group">
            <input type="text" name="body" class="form-control" placeholder="Balas...">
            <button class="btn btn-outline-success">Kirim</button>
          </div>
        </form>

        {{-- Daftar balasan (tanpa nested) --}}
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
      </div>
    @endforeach

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $discussions->links() }}
    </div>
  @else
    <div class="alert alert-info">
      @if(($q ?? request('q')))
        Tidak ada diskusi yang cocok dengan pencarian <strong>"{{ $q ?? request('q') }}"</strong>.
        <a href="{{ route('discussions.index') }}" class="alert-link">Tampilkan semua diskusi</a>.
      @else
        Belum ada diskusi. Jadilah yang pertama memulai percakapan!
      @endif
    </div>
  @endif
@endsection
