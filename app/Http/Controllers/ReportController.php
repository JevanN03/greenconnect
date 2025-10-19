<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Http\Requests\StoreReportRequest;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function create() {
        return view('reports.create');
    }

    public function store(StoreReportRequest $r) {
        $data = $r->validated();

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
        // authorize tidak perlu per item karena sudah difilter by user_id
        return view('reports.check', compact('myReports'));
    }

}
