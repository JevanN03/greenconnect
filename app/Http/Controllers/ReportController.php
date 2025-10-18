<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create() {
        return view('reports.create');
    }

    public function store(Request $r) {
        $data = $r->validate([
            'title' => 'required|string|max:150',
            'description' => 'required|string',
            'photo' => 'nullable|image|max:2048'
        ]);

        $path = null;
        if ($r->hasFile('photo')) {
            $path = $r->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => auth()->id(),
            'title' => $data['title'],
            'description' => $data['description'],
            'photo_path' => $path,
            'status' => 'baru'
        ]);

        return redirect()->route('reports.check')->with('success','Laporan dikirim.');
    }

    public function check() {
        $myReports = Report::where('user_id', auth()->id())->latest()->paginate(10);
        return view('reports.check', compact('myReports'));
    }
}
