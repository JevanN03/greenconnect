@extends('layouts.app')

@section('content')
  <article class="mb-4">

    {{-- Header judul + meta --}}
    <header class="mb-3">
      <h1 class="h3 fw-bold mb-2">{{ $article->title }}</h1>

      <p class="text-muted small mb-1">
        {{ $article->created_at->format('d M Y') }}
        @if($article->author) • oleh {{ $article->author->name }} @endif
      </p>

      @if($article->source)
        <p class="text-muted small mb-0">
          <span class="fst-italic">Sumber:</span>
          @php $src = $article->source; @endphp
          @if(\Illuminate\Support\Str::startsWith($src, ['http://','https://']))
            <a href="{{ $src }}" target="_blank" rel="noopener">{{ $src }}</a>
          @else
            {{ $src }}
          @endif
        </p>
      @endif
    </header>

    {{-- Gambar Sampul --}}
    @if($article->cover_url)
      <img src="{{ $article->cover_url }}" alt="Gambar Sampul {{ $article->title }}" class="img-fluid rounded mb-3">
    @endif

    {{-- Konten artikel (HTML aman karena sudah dibersihkan dengan Purify saat disimpan) --}}
    <div class="content-body">
      {!! $article->content !!}
    </div>

    <div class="mt-4">
      <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary btn-sm">← Kembali ke daftar artikel</a>
    </div>
  </article>
@endsection

@push('styles')
<style>
  /* Styling ringan untuk konten */
  .content-body h1, .content-body h2, .content-body h3, .content-body h4 {
    margin-top: 1.25rem;
    margin-bottom: .75rem;
    font-weight: 700;
  }
  .content-body p { margin-bottom: .9rem; }
  .content-body ul, .content-body ol { padding-left: 1.2rem; margin-bottom: .9rem; }
  .content-body blockquote {
    border-left: 4px solid #198754; /* bootstrap success */
    padding-left: .75rem;
    color: #6c757d;
    margin: .9rem 0;
  }
  .content-body img { max-width: 100%; height: auto; border-radius: .5rem; }
  .content-body table { width: 100%; margin: 1rem 0; }
  .content-body table th, .content-body table td { padding: .5rem; border: 1px solid #dee2e6; }
  .content-body pre, .content-body code {
    background: #f8f9fa;
    border-radius: .5rem;
  }
  .content-body pre { padding: .75rem; overflow: auto; }
</style>
@endpush
