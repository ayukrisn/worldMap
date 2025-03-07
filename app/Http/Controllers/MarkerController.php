<?php

namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarkerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $markers = Marker::where('user_id', Auth::id())->get();
        return response()->json($markers);
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
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $marker = Marker::create([
            'user_id' => Auth::id(),
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json($marker, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marker $marker)
    {
        // Ensure the user owns the marker
        if ($marker->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            // 'latitude' => 'required|numeric',
            // 'longitude' => 'required|numeric',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $marker->update($request->only(['title', 'description']));

        return response()->json($marker);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marker $marker)
    {
        // Ensure the user owns the marker
        if ($marker->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $marker->delete();

        return response()->json(['message' => 'Marker deleted successfully']);
    }
}
