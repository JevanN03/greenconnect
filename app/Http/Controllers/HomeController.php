<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'articles_total' => Article::count(),
            'reports_month'  => Report::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count(),
            'reports_done'   => Report::where('status', 'selesai')->count(),
        ];

        return view('welcome-landing', compact('stats'));
    }
}
