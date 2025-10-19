<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $q = request('q');
        $articles = Article::when($q, fn($w)=>$w->where('title','like',"%$q%"))
            ->latest()->paginate(10);
        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() { return view('admin.articles.create'); }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $r) {
        $data = $r->validate([
            'title'=>'required|max:150',
            'excerpt'=>'nullable|string',
            'content'=>'required|string',
        ]);
        $data['user_id'] = auth()->id();
        Article::create($data);
        return redirect()->route('articles.index')->with('success','Artikel dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article) { return view('admin.articles.edit', compact('article')); }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $r, Article $article) {
        $data = $r->validate([
            'title'=>'required|max:150',
            'excerpt'=>'nullable|string',
            'content'=>'required|string',
        ]);
        $article->update($data);
        return redirect()->route('articles.index')->with('success','Artikel diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article) {
        $article->delete();
        return back()->with('success','Artikel dihapus.');
    }
}
