<?php

namespace App\Http\Controllers;

use App\Models\Readings;
use App\Models\Gloves;
use Illuminate\Http\Request;

class ReadingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $glove = Gloves::where('serial_number', $request->serial_number)->first();
        $readings = Readings::where('gloves_id', $glove->id);
        return response()->json($readings, 200);
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
        $glove = Gloves::where('serial_number', $request->serial_number)->first();
        $readings = New Readings;
        $readings->gloves_id = $glove->id;
        $readings->finger_1 = $request->finger_1;
        $readings->finger_2 = $request->finger_2;
        $readings->finger_3 = $request->finger_3;
        $readings->finger_4 = $request->finger_4;
        $readings->finger_5 = $request->finger_5;
        $readings->save();

        return response()->json(['message' => 'Reading created successfully!', 'serial_number' => $request->serial_number], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Readings $readings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Readings $readings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Readings $readings)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Readings $readings)
    {
        //
    }
}
