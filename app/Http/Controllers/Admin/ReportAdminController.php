<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $query = Report::with('user')
            ->when($q, function ($w) use ($q) {
                // Kelompokkan pencarian agar OR tidak "lepas"
                $w->where(function ($qq) use ($q) {
                    $qq->where('title', 'like', "%{$q}%")
                       ->orWhere('status', 'like', "%{$q}%")
                       ->orWhere('description', 'like', "%{$q}%");
                });
            })
            ->latest();

        $reports = $query->paginate(15);

        // Tambahkan kembali query string ke link pagination
        $reports->appends($request->query());

        return view('admin.reports.index', compact('reports'));
    }

    public function edit(Report $report)
    {
        return view('admin.reports.edit', compact('report'));
    }

    public function update(Request $request, Report $report)
    {
        $data = $request->validate([
            'status'      => 'required|string|in:open,in_progress,closed', // sesuaikan enum/status kamu
            'admin_notes' => 'nullable|string',
        ]);

        $report->update($data);

        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    public function destroy(Report $report)
    {
        $report->delete();

        return redirect()
            ->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
