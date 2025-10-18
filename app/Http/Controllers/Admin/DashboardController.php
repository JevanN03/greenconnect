<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() {
        // ringkasan + data chart (kita isi garis besar, detail chart di Tahap 2)
        $counts = [
            'total_reports' => Report::count(),
            'baru' => Report::where('status','baru')->count(),
            'diproses' => Report::where('status','diproses')->count(),
            'selesai' => Report::where('status','selesai')->count(),
            'discussions' => Discussion::count(),
            'replies' => DiscussionReply::count(),
        ];

        // contoh agregasi harian 7 hari terakhir (detail chart nanti)
        $daily = Report::select(DB::raw('DATE(created_at) as d'), DB::raw('count(*) as c'))
            ->where('created_at','>=', now()->subDays(6)->startOfDay())
            ->groupBy('d')->orderBy('d')->get();

        return view('admin.dashboard', compact('counts','daily'));
    }

    // CRUD laporan
    public function reports() {
        $reports = Report::latest()->paginate(20);
        return view('admin.reports.index', compact('reports'));
    }

    public function editReport(Report $report) {
        return view('admin.reports.edit', compact('report'));
    }

    public function updateReport(Request $r, Report $report) {
        $data = $r->validate([
            'status' => 'required|in:baru,diproses,selesai',
            'title' => 'required|string|max:150',
            'description' => 'required|string'
        ]);
        $report->update($data);
        return redirect()->route('admin.reports')->with('success','Laporan diperbarui.');
    }

    public function destroyReport(Report $report) {
        $report->delete();
        return back()->with('success','Laporan dihapus.');
    }

    public function adminReply(Request $r, Discussion $discussion) {
        $r->validate(['body'=>'required']);
        DiscussionReply::create([
            'discussion_id'=>$discussion->id,
            'user_id'=>auth()->id(), // admin yang login
            'body'=>$r->body
        ]);
        return back()->with('success','Admin menanggapi diskusi.');
    }
}
