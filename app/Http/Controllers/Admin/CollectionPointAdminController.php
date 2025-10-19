<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionPoint;
use Illuminate\Http\Request;

class CollectionPointAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $q = request('q');
        $points = CollectionPoint::when($q, fn($w)=>$w->where('name','like',"%$q%")
            ->orWhere('address','like',"%$q%"))->orderBy('name')->paginate(15);
        return view('admin.collection_points.index', compact('points'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() { return view('admin.collection_points.create'); }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $r) {
        $data = $r->validate([
            'name'=>'required|max:150',
            'contact'=>'nullable|string|max:100',
            'address'=>'required|string',
            'map_link'=>'nullable|url'
        ]);
        CollectionPoint::create($data);
        return redirect()->route('collection-points.index')->with('success','TPA/TPS ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CollectionPoint $collectionPoint)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollectionPoint $collection_point) {
        return view('admin.collection_points.edit', ['point'=>$collection_point]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $r, CollectionPoint $collection_point) {
        $data = $r->validate([
            'name'=>'required|max:150',
            'contact'=>'nullable|string|max:100',
            'address'=>'required|string',
            'map_link'=>'nullable|url'
        ]);
        $collection_point->update($data);
        return redirect()->route('collection-points.index')->with('success','TPA/TPS diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CollectionPoint $collection_point) {
        $collection_point->delete();
        return back()->with('success','TPA/TPS dihapus.');
    }
}
