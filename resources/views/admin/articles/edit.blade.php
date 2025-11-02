@extends('layouts.app')

@section('content')
<h3 class="mb-3">Edit Artikel</h3>

<form id="article-form"
      novalidate
      method="POST"
      action="{{ route('admin.articles.update', $article) }}"
      class="border p-3 p-md-4 rounded shadow-sm"
      enctype="multipart/form-data">
  @csrf
  @method('PUT')

  {{-- Judul --}}
  <div class="mb-3">
    <label class="form-label">Judul</label>
    <input type="text" name="title" class="form-control"
          value="{{ old('title', $article->title) }}" required>
  </div>

  {{-- Ringkasan --}}
  <div class="mb-3">
    <label class="form-label">Ringkasan (1–2 paragraf)</label>
    <textarea name="excerpt" class="form-control" rows="3"
              placeholder="Ringkas isi artikel...">{{ old('excerpt', $article->excerpt) }}</textarea>
    <div class="form-text">Ditampilkan pada kartu artikel/preview.</div>
  </div>

  {{-- Konten (TinyMCE) --}}
  <div class="mb-3">
    <label class="form-label">Konten (Rich Text)</label>
    <textarea id="editor" name="content" class="form-control" rows="14">{{ old('content') }}</textarea>
  </div>

  {{-- Gambar Sampul --}}
  <div class="mb-3">
    <label class="form-label">Gambar Sampul (Hero Image)</label>
    @if($article->cover_url)
      <div class="mb-2">
        <img src="{{ $article->cover_url }}" alt="Sampul Saat Ini" class="img-fluid rounded" style="max-height:180px">
      </div>
    @endif
    <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
    <div class="form-text">Kosongkan jika tidak diganti. Maks 2MB. Format: JPG/PNG/WebP.</div>
    <div id="cover_preview_wrap" class="mt-2 d-none">
      <img id="cover_preview" src="#" alt="Preview Sampul Baru" class="img-fluid rounded" style="max-height:180px">
    </div>
  </div>

  {{-- Sumber --}}
  <div class="mb-3">
    <label class="form-label">Sumber</label>
    <input type="text" name="source" class="form-control"
          value="{{ old('source', $article->source) }}"
          placeholder="URL atau keterangan sumber (opsional)">
  </div>

  <div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
@endsection

@push('scripts')
  <script src="https://cdn.tiny.cloud/1/{{ config('services.tinymce.key', env('TINYMCE_API_KEY','no-api-key')) }}/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    (function () {
      const form = document.getElementById('article-form');

      function initTiny() {
        if (!window.tinymce) { console.error('TinyMCE gagal dimuat.'); return; }

        tinymce.init({
          selector: '#editor',
          menubar: false,
          branding: false,
          height: 500,

          // Gunakan hanya plugin gratis yang stabil (hindari 404)
          plugins: 'lists link table code codesample advlist autoresize',

          toolbar: [
            'undo redo | blocks | bold italic underline strikethrough removeformat | ' +
            'alignleft aligncenter alignright alignjustify | ' +
            'bullist numlist outdent indent | ' +
            'fontsizeselect forecolor backcolor | ' +
            'table link | code codesample'
          ].join(''),

          fontsize_formats: '10px 12px 14px 16px 18px 20px 24px 28px 32px 36px',
          paste_as_text: false,
          convert_urls: false
        });
      }

      document.addEventListener('DOMContentLoaded', initTiny);

      // Validasi submit yang aman (tanpa required native)
      form.addEventListener('submit', function (e) {
        try { if (window.tinymce) tinymce.triggerSave(); } catch(_) {}

        const field = document.querySelector('textarea[name="content"]');
        const html  = (field?.value || '').trim();
        const text  = html.replace(/<[^>]*>/g,'').replace(/&nbsp;/g,' ').trim();

        if (!text) {
          e.preventDefault();
          alert('Konten wajib diisi.');
          field?.focus();
        }
      });

      // Preview sampul (kalau ada)
      const input = document.getElementById('cover_image');
      const wrap  = document.getElementById('cover_preview_wrap');
      const img   = document.getElementById('cover_preview');
      if (input && wrap && img) {
        input.addEventListener('change', function(){
          const f = this.files && this.files[0];
          if (!f) { wrap.classList.add('d-none'); return; }
          img.src = URL.createObjectURL(f);
          wrap.classList.remove('d-none');
        });
      }
    })();
  </script>
@endpush


