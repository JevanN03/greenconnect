@extends('layouts.app')

@section('content')
<h3 class="mb-3">Tambah TPA/TPS</h3>

<form method="POST" action="{{ route('admin.collection-points.store') }}" 
      class="border p-3 p-md-4 rounded shadow-sm js-confirm-save"
      data-title="Simpan TPA/TPS?" data-text="Pastikan link peta dan alamat sudah benar.">
  @csrf

<div class="mb-3">
    <label class="form-label">Nama <span class="text-danger">*</span></label>
    <input name="name" id="cp-name" class="form-control" value="{{ old('name') }}" required>
    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="address" id="cp-address" class="form-control" rows="2">{{ old('address') }}</textarea>
    @error('address') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Kontak</label>
    <input name="contact" class="form-control" value="{{ old('contact') }}">
    @error('contact') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-2">
    <label class="form-label">Link/Koordinat Google Maps (opsional)</label>
    <input name="map_url" id="cp-mapurl" class="form-control" value="{{ old('map_url') }}"
          placeholder="Tempel link Google Maps (Share → Copy link) / atau koordinat lat,lng">
    <div class="form-text">Jika kosong, pratinjau peta akan memakai <strong>Nama</strong>.</div>
    @error('map_url') <div class="text-danger small">{{ $message }}</div> @enderror
  </div>

  <div class="mb-3">
    <label class="form-label">Preview Peta</label>
    <div class="ratio ratio-16x9 border rounded">
      <iframe id="cp-iframe" src="" style="border:0" loading="lazy"></iframe>
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
  // refs
  const $name   = document.getElementById('cp-name');
  const $mapUrl = document.getElementById('cp-mapurl');
  const $addr   = document.getElementById('cp-address');
  const $iframe = document.getElementById('cp-iframe');

  // ekstrak koordinat dari berbagai pola url/teks
  function parseCoords(s) {
    if (!s) return null;
    let m = s.match(/@\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);             // @lat,lng
    if (m) return [parseFloat(m[1]), parseFloat(m[2])];
    m = s.match(/!3d\s*(-?\d+\.\d+)\s*!4d\s*(-?\d+\.\d+)/);             // !3dLAT!4dLNG
    if (m) return [parseFloat(m[1]), parseFloat(m[2])];
    m = s.match(/[?&]q=\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)/);            // q=lat,lng
    if (m) return [parseFloat(m[1]), parseFloat(m[2])];
    m = s.match(/^\s*(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)\s*$/);             // plain "lat,lng"
    if (m) return [parseFloat(m[1]), parseFloat(m[2])];
    return null;
  }

  // bangun url embed: koordinat (dari map_url) → Nama → map_url → Alamat
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
    const src = buildEmbedUrl(
      $name   ? $name.value   : '',
      $mapUrl ? $mapUrl.value : '',
      $addr   ? $addr.value   : ''
    );
    $iframe.src = src || 'about:blank';
  }

  // debounce agar tidak terlalu sering reload iframe
  let t; const deb = fn => { clearTimeout(t); t = setTimeout(fn, 250); };

  [$name, $mapUrl, $addr].forEach(el => el && el.addEventListener('input', () => deb(updatePreview)));

  // render awal (pakai old() jika ada)
  updatePreview();
</script>
@endpush