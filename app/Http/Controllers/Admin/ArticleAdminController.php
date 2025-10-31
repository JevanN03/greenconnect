<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Stevebauman\Purify\Facades\Purify;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class ArticleAdminController extends Controller
{
    public function index()
    {
        $q = request('q');

        $articles = Article::when($q, fn ($w) =>
                $w->where('title', 'like', "%{$q}%")
            )
            ->latest()
            ->paginate(10);

        $articles->appends(request()->query());

        return view('admin.articles.index', compact('articles', 'q'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:150',
            'excerpt'      => 'nullable|string',
            'content'      => 'required|string', // HTML dari CKEditor
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=600,min_height=400',
            'source'       => 'nullable|string|max:255',
        ]);

        // Sanitasi HTML (CKEditor) agar aman
        $data['content'] = Purify::clean($data['content']);

        // Proses cover jika ada
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $this->processCover($request->file('cover_image'));
        }

        $data['user_id'] = auth()->id();

        Article::create($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel dibuat.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:150',
            'excerpt'      => 'nullable|string',
            'content'      => 'required|string', // HTML dari CKEditor
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=600,min_height=400',
            'source'       => 'nullable|string|max:255',
        ]);

        // Sanitasi HTML
        $data['content'] = Purify::clean($data['content']);

        // Ganti cover jika dikirim baru
        if ($request->hasFile('cover_image')) {
            // hapus file lama + thumb
            if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
                Storage::disk('public')->delete($article->cover_image);
                $oldThumb = preg_replace('#^covers/#', 'covers/thumbs/', $article->cover_image);
                if ($oldThumb && Storage::disk('public')->exists($oldThumb)) {
                    Storage::disk('public')->delete($oldThumb);
                }
            }
            $data['cover_image'] = $this->processCover($request->file('cover_image'));
        }

        $article->update($data);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel diperbarui.');
    }

    public function destroy(Article $article)
    {
        if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
            Storage::disk('public')->delete($article->cover_image);
            $oldThumb = preg_replace('#^covers/#', 'covers/thumbs/', $article->cover_image);
            if ($oldThumb && Storage::disk('public')->exists($oldThumb)) {
                Storage::disk('public')->delete($oldThumb);
            }
        }

        $article->delete();

        return back()->with('success', 'Artikel dihapus.');
    }

    /**
     * Proses upload cover:
     * - Pilih driver (Imagick > GD). Jika keduanya tidak ada, simpan file apa adanya (fallback).
     * - Resize max width 1600 (rasio terjaga), simpan WebP q~82.
     * - Buat thumbnail 600px WebP.
     * - Return path relatif (mis. covers/uuid.webp).
     */
    private function processCover(\Illuminate\Http\UploadedFile $file): string
    {
        $hasImagick = extension_loaded('imagick');
        $hasGd      = extension_loaded('gd');

        if (!$hasImagick && !$hasGd) {
            // Fallback jika tidak ada driver image: simpan tanpa proses
            return $file->store('covers', 'public');
        }

        $driver  = $hasImagick ? new ImagickDriver() : new GdDriver();
        $manager = new ImageManager($driver);

        $image = $manager->read($file->getPathname());

        // Resize max width 1600
        $image->scale(width: min(1600, $image->width()));

        $name      = Str::uuid()->toString() . '.webp';
        $pathFull  = 'covers/' . $name;
        $pathThumb = 'covers/thumbs/' . $name;

        // Simpan full
        Storage::disk('public')->put($pathFull, $image->toWebp(82));

        // Thumbnail 600px
        $thumb = clone $image;
        $thumb->scale(width: 600);
        Storage::disk('public')->put($pathThumb, $thumb->toWebp(82));

        return $pathFull;
    }
}
