<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionPoint;
use Illuminate\Http\Request;

class CollectionPointAdminController extends Controller
{
    public function index() {
        $q = request('q');
        $points = CollectionPoint::when($q, fn($w)=>$w->where('name','like',"%$q%")
            ->orWhere('address','like',"%$q%"))
            ->orderBy('name')->paginate(15);
        $points->appends(request()->query());
        return view('admin.collection_points.index', compact('points','q'));
    }

    public function create() { return view('admin.collection_points.create'); }

    public function store(Request $r) {
        $data = $r->validate([
            'name'=>'required|max:150',
            'contact'=>'nullable|string|max:100',
            'address'=>'required|string',
            'map_link'=>'nullable|url'
        ]);
        CollectionPoint::create($data);
        return redirect()->route('admin.collection-points.index')->with('success','TPA/TPS ditambahkan.');
    }

    public function edit(CollectionPoint $collection_point) {
        return view('admin.collection_points.edit', ['point'=>$collection_point]);
    }

    public function update(Request $r, CollectionPoint $collection_point) {
        $data = $r->validate([
            'name'=>'required|max:150',
            'contact'=>'nullable|string|max:100',
            'address'=>'required|string',
            'map_link'=>'nullable|url'
        ]);
        $collection_point->update($data);
        return redirect()->route('admin.collection-points.index')->with('success','TPA/TPS diperbarui.');
    }

    public function destroy(CollectionPoint $collection_point) {
        $collection_point->delete();
        return back()->with('success','TPA/TPS dihapus.');
    }
}
