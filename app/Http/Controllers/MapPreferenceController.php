<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MapPreference;

class MapPreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
     * Show the user's current map preference
     */
    public function show()
    {
        $user = Auth::user();
        $preference = MapPreference::where('user_id', $user->id)->first();

        return response()->json([
            'map_type' => $preference ? $preference->map_type : 'open_street_map'
        ]);
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
    public function update(Request $request)
    {
        $request->validate([
            'map_type' => 'required|string|in:google_maps,open_street_map,mapbox'
        ]);

        $user = Auth::user();

        MapPreference::updateOrCreate(
            ['user_id' => $user->id],
            ['map_type' => $request->map_type]
        );

        return response()->json(['message' => 'Map preference updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
