@extends('layouts.app')

@section('content')
<h3 class="mb-3">Edit TPA/TPS</h3>

<form method="POST" action="{{ route('admin.collection-points.update', $point) }}" 
      class="border p-3 p-md-4 rounded shadow-sm js-confirm-save"
      data-title="Simpan TPA/TPS?" data-text="Pastikan link peta dan alamat sudah benar.">
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
    <label class="form-label">Link/Koordinat Google Maps (opsional)</label>
    <input name="map_url" id="cp-mapurl" class="form-control" value="{{ old('map_url', $point->map_url) }}">
    <div class="form-text">
      Boleh diisi koordinat <code>lat,lng</code> atau link Google Maps. Jika kosong, pratinjau akan memakai <strong>Nama</strong>.
    </div>
  </div>

  <div class="mb-3">
    <label class="form-label">Preview Peta</label>
    <div class="ratio ratio-16x9 border rounded">
      <iframe id="cp-iframe" src="{{ $point->map_embed_url }}" style="border:0" loading="lazy"></iframe>
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

// Bangun URL embed: koordinat (paling akurat) → Nama → map_url → alamat
  function buildEmbedUrl(name, mapUrl, address) {
    const has = v => v && String(v).trim().length > 0;

    if (has(mapUrl)) {
      const coords = parseCoords(mapUrl);
      if (coords) return `https://www.google.com/maps?q=${coords[0]},${coords[1]}&z=16&output=embed`;
    }
    if (has(name))   return 'https://www.google.com/maps?q=' + encodeURIComponent(name) + '&z=16&output=embed';
    if (has(mapUrl)) {
      if (mapUrl.includes('output=embed') || mapUrl.includes('/embed')) return mapUrl;
      return 'https://www.google.com/maps?q=' + encodeURIComponent(mapUrl) + '&z=16&output=embed';
    }
    if (has(address)) return 'https://www.google.com/maps?q=' + encodeURIComponent(address) + '&z=16&output=embed';
    return '';
  }

  function updatePreview() {
    if (!$iframe) return;
    const name   = $name   ? $name.value   : '';
    const mapUrl = $mapUrl ? $mapUrl.value : '';
    const addr   = $addr   ? $addr.value   : '';
    const src = buildEmbedUrl(name, mapUrl, addr);
    $iframe.src = src || 'about:blank';
  }

  let t; const deb = fn => { clearTimeout(t); t = setTimeout(fn, 250); };

  [$name, $mapUrl, $addr].forEach(el => el && el.addEventListener('input', () => deb(updatePreview)));

  updatePreview();
</script>
@endpush


