@extends('layouts.app')

@section('content')
<h3 class="mb-3">Tambah Artikel</h3>

<form id="article-form"
      method="POST"
      action="{{ route('admin.articles.store') }}"
      class="border p-3 p-md-4 rounded shadow-sm"
      enctype="multipart/form-data">
  @csrf

  {{-- Judul --}}
  <div class="mb-3">
    <label class="form-label">Judul</label>
    <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
  </div>

  {{-- Ringkasan --}}
  <div class="mb-3">
    <label class="form-label">Ringkasan (1–2 paragraf)</label>
    <textarea name="excerpt" class="form-control" rows="3" placeholder="Ringkas isi artikel...">{{ old('excerpt') }}</textarea>
    <div class="form-text">Ditampilkan pada kartu artikel/preview.</div>
  </div>

  {{-- Konten (WYSIWYG) --}}
  <div class="mb-3">
    <label class="form-label">Konten (Rich Text)</label>
    {{-- Textarea EDITOR: TIDAK punya name 'content' dan TIDAK required --}}
    <textarea id="editor" class="form-control" rows="12">{{ old('content') }}</textarea>
    {{-- Hidden input yang DIKIRIM ke server --}}
    <input type="hidden" name="content" id="content_hidden" value="">
    <div class="form-text">Gunakan toolbar untuk format teks, daftar, tautan, tabel, dsb.</div>
  </div>

  {{-- Gambar Sampul --}}
  <div class="mb-3">
    <label class="form-label">Gambar Sampul (Hero Image)</label>
    <input type="file" name="cover_image" id="cover_image" class="form-control" accept="image/*">
    <div class="form-text">Maks 2MB. Format: JPG/PNG/WebP. Disarankan rasio lebar.</div>
    <div id="cover_preview_wrap" class="mt-2 d-none">
      <img id="cover_preview" src="#" alt="Preview Sampul" class="img-fluid rounded" style="max-height:180px">
    </div>
  </div>

  {{-- Sumber --}}
  <div class="mb-3">
    <label class="form-label">Sumber</label>
    <input type="text" name="source" class="form-control" value="{{ old('source') }}" placeholder="URL atau keterangan sumber (opsional)">
  </div>

  <div class="d-flex gap-2">
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline-secondary">Batal</a>
  </div>
</form>
@endsection

@push('scripts')
  {{-- CKEditor 5 --}}
  <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
  <script>
    let editorInstance;

    ClassicEditor.create(document.querySelector('#editor'), {
      toolbar: [
        'heading','|','bold','italic','underline','link',
        'bulletedList','numberedList','blockQuote','insertTable','|',
        'undo','redo'
      ]
    }).then(editor => {
      editorInstance = editor;
    }).catch(console.error);

    // Sinkronisasi konten editor ke hidden input saat submit
    const form = document.getElementById('article-form');
    form.addEventListener('submit', function(e){
      const data = (editorInstance ? editorInstance.getData() : '').trim();

      if (!data) {
        e.preventDefault();
        alert('Konten wajib diisi.');
        return;
      }

      document.getElementById('content_hidden').value = data;
    });

    // Preview gambar sampul
    const input = document.getElementById('cover_image');
    const wrap  = document.getElementById('cover_preview_wrap');
    const img   = document.getElementById('cover_preview');
    if (input) {
      input.addEventListener('change', function(){
        const f = this.files && this.files[0];
        if (!f) { wrap.classList.add('d-none'); return; }
        const url = URL.createObjectURL(f);
        img.src = url;
        wrap.classList.remove('d-none');
      });
    }
  </script>
@endpush
