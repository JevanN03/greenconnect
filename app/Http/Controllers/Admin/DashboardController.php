<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Discussion;
use App\Models\DiscussionReply;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

        // HARIAN: 7 hari terakhir
        $daily = Report::select(
            DB::raw('DATE(created_at) as d'),
            DB::raw('COUNT(*) as c'))->where('created_at','>=', now()->subDays(6)->startOfDay())->groupBy('d')->orderBy('d')->get();

        // MINGGUAN: 8 minggu terakhir (YearWeek untuk akurasi lintas tahun)
        $weekly = Report::select(
            DB::raw("YEARWEEK(created_at, 3) as yw"), // mode ISO week, 3=Monday-first
            DB::raw("DATE_FORMAT(DATE_SUB(DATE(created_at), INTERVAL (WEEKDAY(created_at)) DAY),'%Y-%m-%d') as week_start"),
            DB::raw('COUNT(*) as c'))->where('created_at','>=', now()->startOfWeek()->subWeeks(7))->groupBy('yw','week_start')->orderBy('week_start')->get();

        // BULANAN: 12 bulan terakhir
        $monthly = Report::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as ym"),
            DB::raw('COUNT(*) as c'))->where('created_at','>=', now()->startOfMonth()->subMonths(11))->groupBy('ym')->orderBy('ym')->get();

        return view('admin.dashboard', compact('counts','daily','weekly','monthly'));
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

    public function adminDiscussions(Request $request){
        $q = $request->query('q');

        $discussions = Discussion::with(['user', 'replies.user'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('title', 'like', "%{$q}%")
                        ->orWhere('body', 'like', "%{$q}%");
                });
            })
            ->latest()
            ->paginate(20);

        // Pertahankan query string saat pindah halaman (hindari warning IDE)
        $discussions->appends($request->query());

        return view('admin.discussions.index', [
            'discussions' => $discussions,
            'q'           => $q,
        ]);
    }
}
