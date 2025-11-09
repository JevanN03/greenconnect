<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionPoint;
use Illuminate\Http\Request;

class CollectionPointAdminController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');

        $points = CollectionPoint::when($q, function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                ->orWhere('address', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(12);

        $points->appends($request->query());

        return view('admin.collection_points.index', compact('points'));
    }

    public function create() { return view('admin.collection_points.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'address' => 'required|string|max:255',
            'contact' => 'nullable|string|max:100',
            'map_url' => 'nullable|string|max:500',
        ]);

        CollectionPoint::create($data);

        return redirect()
            ->route('admin.collection-points.index')
            ->with('success', 'TPA/TPS berhasil ditambahkan.');
    }

    public function edit(CollectionPoint $collection_point) {
        return view('admin.collection_points.edit', ['point'=>$collection_point]);
    }

    public function update(Request $request, CollectionPoint $collectionPoint)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:150',
            'address' => 'required|string|max:255',
            'contact' => 'nullable|string|max:100',
            'map_url' => 'nullable|string|max:500',
        ]);

        $collectionPoint->update($data);

        return redirect()
            ->route('admin.collection-points.index')
            ->with('success', 'TPA/TPS berhasil diperbarui.');
    }

    public function destroy(CollectionPoint $collectionPoint)
    {
        $collectionPoint->delete();

        return redirect()
            ->route('admin.collection-points.index')
            ->with('success', 'TPA/TPS berhasil dihapus.');
    }
}
