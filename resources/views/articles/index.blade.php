@extends('layouts.app')
@section('content')
<h2 class="mb-3">Artikel Edukasi</h2>
@foreach($articles as $a)
  <div class="mb-3 p-3 border rounded">
    <h5 class="mb-1"><a href="{{ route('articles.show',$a) }}">{{ $a->title }}</a></h5>
    <p class="text-muted small mb-1">{{ $a->created_at->format('d M Y') }}</p>
    <p class="mb-0">{{ $a->excerpt ?? Str::limit(strip_tags($a->content),120) }}</p>
  </div>
@endforeach
{{ $articles->links() }}
@endsection
