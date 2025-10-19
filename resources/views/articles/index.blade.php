@extends('layouts.app')

@section('content')
  <h2 class="mb-3">Artikel Edukasi</h2>

  {{-- Form Pencarian --}}
  <form class="row g-2 mb-3" method="GET" action="{{ route('articles.index') }}">
    <div class="col-sm-8 col-md-6">
      <input
        type="text"
        name="q"
        class="form-control"
        placeholder="Cari artikel (judul/konten)..."
        value="{{ old('q', $q ?? request('q')) }}"
      >
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary">Cari</button>
    </div>
    @if(($q ?? request('q')))
      <div class="col-auto">
        <a class="btn btn-outline-danger" href="{{ route('articles.index') }}">Reset</a>
      </div>
    @endif
  </form>

  @if($articles->count())
    @foreach($articles as $a)
      <div class="mb-3 p-3 border rounded">
        <h5 class="mb-1">
          <a href="{{ route('articles.show', $a) }}" class="text-decoration-none">
            {{ $a->title }}
          </a>
        </h5>
        <p class="text-muted small mb-2">
          {{ $a->created_at->format('d M Y') }}
          @if($a->author) • oleh {{ $a->author->name }} @endif
        </p>
        <p class="mb-0">
          {{ $a->excerpt ? $a->excerpt : \Illuminate\Support\Str::limit(strip_tags($a->content), 140) }}
        </p>
      </div>
    @endforeach

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $articles->links() }}
    </div>
  @else
    <div class="alert alert-info">
      @if(($q ?? request('q')))
        Tidak ada artikel yang cocok dengan pencarian <strong>"{{ $q ?? request('q') }}"</strong>.
        <a href="{{ route('articles.index') }}" class="alert-link">Tampilkan semua artikel</a>.
      @else
        Belum ada artikel untuk saat ini.
      @endif
    </div>
  @endif
@endsection
