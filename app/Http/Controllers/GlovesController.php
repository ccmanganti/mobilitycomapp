<?php

namespace App\Http\Controllers;

use App\Models\Gloves;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GlovesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $gloves = Gloves::all();
        return response()->json($gloves);
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
        $glove = New Gloves;
        $glove->serial_number = $request->serial_number;

        return response()->json(['isValid' => True], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Gloves $gloves)
    {
        return view('registerglove');
    }

    /**
     * Display the specified resource.
     */
    public function showForUser()
    {
        $gloves = Auth::user()->gloves;
        return view('dashboard', compact('gloves'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gloves $gloves)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $gloves = Gloves::where('serial_number', $request->serial_number)->first();
        if (!$gloves){
            return response()->json(['message' => 'Glove not found'], 404);
        }
        if ($gloves->user_id){
            return response()->json(['message' => 'Glove already bound to user'], 400);
        }
        $user = Auth::id();
        $gloves->user_id = $user;
        $gloves->save();
        return response()->json(['success' => true, 'message' => 'Glove successfully registered.', 'gloves' => $gloves], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gloves $gloves)
    {
        $gloves->delete();
        return response()->json(['message' => 'Glove deleted successfully'], 200);
    }
    
    //////////////////////
    // CUSTOM FUNCTIONS //
    //////////////////////

    public function check_serial(Request $request){
        $checkGloves = Gloves::where('serial_number', $request->serial_number)->first();
        if (!$checkGloves){
            return response()->json(['isValid' => False], 200);
        }
        return response()->json(['isValid' => True], 200);
    }
}