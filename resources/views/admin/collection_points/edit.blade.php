@extends('layouts.app')

@section('content')
<h3 class="mb-3">Edit TPA/TPS</h3>

<form method="POST" action="{{ route('admin.collection-points.update', $point) }}" class="border p-3 p-md-4 rounded shadow-sm">
  @csrf
  @method('PUT')

  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input name="name" class="form-control" value="{{ old('name', $point->name) }}" required>
  </div>

  <div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="address" class="form-control" rows="2" required>{{ old('address', $point->address) }}</textarea>
  </div>

  <div class="mb-3">
    <label class="form-label">Kontak</label>
    <input name="contact" class="form-control" value="{{ old('contact', $point->contact) }}">
  </div>

  <div class="mb-2">
    <label class="form-label">Link Peta Google Maps (Share -> Copy Link)</label>
    <input name="map_url" id="map_url" class="form-control" value="{{ old('map_url', $point->map_url) }}">
  </div>

  <div class="mb-3">
    <div class="ratio ratio-16x9 border rounded">
      <iframe id="map_iframe" src="{{ $point->map_embed_url }}" style="border:0" loading="lazy"></iframe>
    </div>
  </div>

  <div class="d-flex gap-2">
    <button class="btn btn-success">Simpan</button>
    <a href="{{ route('admin.collection-points.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
@endsection

@push('scripts')
<script>
  const input  = document.getElementById('map_url');
  const iframe = document.getElementById('map_iframe');

  function parseCoords(s) {
    if (!s) return null;
    // @lat,lng
    let m = s.match(/@\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
    if (m) return [parseFloat(m[1]), parseFloat(m[2])];
    // !3dLAT!4dLNG
    m = s.match(/!3d\s*(-?\d+\.\d+)\s*!4d\s*(-?\d+\.\d+)/);
    if (m) return [parseFloat(m[1]), parseFloat(m[2])];
    // q=lat,lng
    m = s.match(/[?&]q=\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);
    if (m) return [parseFloat(m[1]), parseFloat(m[2])];
    // plain "lat,lng"
    m = s.match(/^\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)\s*$/);
    if (m) return [parseFloat(m[1]), parseFloat(m[2])];
    return null;
  }

  function toEmbed(url, fallbackAddress = '') {
    if (url) {
      const coords = parseCoords(url);
      if (coords) {
        return `https://www.google.com/maps?q=${coords[0]},${coords[1]}&z=16&output=embed`;
      }
      if (url.includes('output=embed') || url.includes('/embed')) return url;
      // kalau shortlink tanpa koordinat, pakai alamat sebagai fallback
      if (fallbackAddress) {
        return 'https://www.google.com/maps?q=' + encodeURIComponent(fallbackAddress) + '&z=16&output=embed';
      }
      return 'https://www.google.com/maps?q=' + encodeURIComponent(url) + '&output=embed';
    }
    if (fallbackAddress) {
      return 'https://www.google.com/maps?q=' + encodeURIComponent(fallbackAddress) + '&z=16&output=embed';
    }
    return '';
  }

  function refresh() {
    const addrInput = document.querySelector('textarea[name="address"], input[name="address"]');
    const address   = addrInput ? addrInput.value.trim() : '';
    iframe.src = toEmbed(input.value.trim(), address);
  }

  if (input && iframe) {
    input.addEventListener('input', refresh);
    const addr = document.querySelector('textarea[name="address"], input[name="address"]');
    if (addr) addr.addEventListener('input', refresh);
    // render awal
    refresh();
  }
</script>
@endpush


