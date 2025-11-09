<?php

namespace App\Http\Controllers;

use App\Models\CollectionPoint;
use Illuminate\Http\Request;

class CollectionPointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $q = $request->query('q');

        $points = CollectionPoint::when($q, function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                ->orWhere('address', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(8);

        $points->appends($request->query());

        return view('collection_points.index', compact('points'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(CollectionPoint $collectionPoint)
    {
        return view('collection_points.show', ['point' => $collectionPoint]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CollectionPoint $collectionPoint)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CollectionPoint $collectionPoint)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CollectionPoint $collectionPoint)
    {
        //
    }
}
