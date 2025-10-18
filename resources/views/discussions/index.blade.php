@extends('layouts.app')
@section('content')
<h2 class="mb-3">Diskusi</h2>

<form method="POST" action="{{ route('discussions.store') }}" class="mb-4">
  @csrf
  <div class="mb-2">
    <input type="text" name="title" class="form-control" placeholder="Judul diskusi" required>
  </div>
  <div class="mb-2">
    <textarea name="body" class="form-control" rows="3" placeholder="Tulis topik diskusi..." required></textarea>
  </div>
  <button class="btn btn-success">Kirim</button>
</form>

@foreach($discussions as $d)
  <div class="mb-4 p-3 border rounded">
    <h5 class="mb-1">{{ $d->title }}</h5>
    <p class="small text-muted mb-2">oleh {{ $d->user->name }} • {{ $d->created_at->diffForHumans() }}</p>
    <p class="mb-3">{{ $d->body }}</p>

    <form method="POST" action="{{ route('discussions.reply',$d) }}" class="mb-2">
      @csrf
      <div class="input-group">
        <input type="text" name="body" class="form-control" placeholder="Balas...">
        <button class="btn btn-outline-success">Kirim</button>
      </div>
    </form>

    @foreach($d->replies as $r)
      <div class="ps-3 border-start ms-2 mb-2">
        <p class="mb-1"><strong>{{ $r->user->name }}</strong> <span class="text-muted small">• {{ $r->created_at->diffForHumans() }}</span></p>
        <p class="mb-0">{{ $r->body }}</p>
      </div>
    @endforeach
  </div>
@endforeach

{{ $discussions->links() }}
@endsection
