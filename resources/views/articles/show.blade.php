@extends('layouts.app')
@section('content')
<h2 class="mb-2">{{ $article->title }}</h2>
<p class="text-muted small">{{ $article->created_at->format('d M Y') }}</p>
<hr>
<div class="lh-base">{!! nl2br(e($article->content)) !!}</div>
@endsection
