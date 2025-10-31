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
      <div class="card mb-3 shadow-sm">
        <div class="row g-0 align-items-stretch">
          @if($a->cover_thumb_url)
            <div class="col-md-3">
              <img src="{{ $a->cover_thumb_url }}" class="img-fluid h-100 w-100 object-fit-cover rounded-start" alt="Sampul {{ $a->title }}">
            </div>
          @endif

          <div class="{{ $a->cover_thumb_url ? 'col-md-9' : 'col-12' }}">
            <div class="card-body d-flex flex-column h-100">
              <h5 class="card-title mb-1">
                <a href="{{ route('articles.show', $a) }}" class="text-decoration-none">
                  {{ $a->title }}
                </a>
              </h5>

              <p class="text-muted small mb-2">
                {{ $a->created_at->format('d M Y') }}
                @if($a->author) • oleh {{ $a->author->name }} @endif
                @if($a->source)
                  • <span class="fst-italic">Sumber:</span>
                  @php $src = $a->source; @endphp
                  @if(\Illuminate\Support\Str::startsWith($src, ['http://','https://']))
                    <a href="{{ $src }}" target="_blank" rel="noopener">
                      {{ \Illuminate\Support\Str::limit($src, 50) }}
                    </a>
                  @else
                    {{ \Illuminate\Support\Str::limit($src, 50) }}
                  @endif
                @endif
              </p>

              <p class="card-text mb-3">
                {{ $a->excerpt ? $a->excerpt : \Illuminate\Support\Str::limit(strip_tags($a->content), 140) }}
              </p>

              <div class="mt-auto">
                <a href="{{ route('articles.show', $a) }}" class="btn btn-success btn-sm">Baca</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endforeach

    {{-- Pagination --}}
    <div class="mt-3">
      {{ $articles->links() }}
    </div>
  @else
    <div class="alert alert-info">
      @if(($q ?? request('q')))
        Tidak ada artikel yang cocok dengan pencarian
        <strong>"{{ $q ?? request('q') }}"</strong>.
        <a href="{{ route('articles.index') }}" class="alert-link">Tampilkan semua artikel</a>.
      @else
        Belum ada artikel untuk saat ini.
      @endif
    </div>
  @endif
@endsection
