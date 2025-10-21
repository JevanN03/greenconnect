<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleAdminController extends Controller
{
    public function index() {
        $q = request('q');
        $articles = Article::when($q, fn($w)=>$w->where('title','like',"%$q%"))
            ->latest()->paginate(10);
        $articles->appends(request()->query());
        return view('admin.articles.index', compact('articles', 'q'));
    }

    public function create() { return view('admin.articles.create'); }

    public function store(Request $r) {
        $data = $r->validate([
            'title'=>'required|max:150',
            'excerpt'=>'nullable|string',
            'content'=>'required|string',
        ]);
        $data['user_id'] = auth()->id();
        Article::create($data);
        return redirect()->route('admin.articles.index')->with('success','Artikel dibuat.');
    }

    public function edit(Article $article) {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $r, Article $article) {
        $data = $r->validate([
            'title'=>'required|max:150',
            'excerpt'=>'nullable|string',
            'content'=>'required|string',
        ]);
        $article->update($data);
        return redirect()->route('admin.articles.index')->with('success','Artikel diperbarui.');
    }

    public function destroy(Article $article) {
        $article->delete();
        return back()->with('success','Artikel dihapus.');
    }
}
