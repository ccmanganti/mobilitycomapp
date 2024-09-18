<?php

namespace App\Http\Controllers;

use App\Models\Actions;
use App\Models\Gloves;
use Illuminate\Http\Request;

class ActionsController extends Controller
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
        $glove = Gloves::where('serial_number', $request->serial_number)->first();
        if ($glove){
            $readings = New Actions;
            $readings->gloves_id = $glove->id;
            $readings->finger_1 = $request->finger_1;
            $readings->finger_2 = $request->finger_2;
            $readings->finger_3 = $request->finger_3;
            $readings->finger_4 = $request->finger_4;
            $readings->finger_5 = $request->finger_5;
            $readings->save();
            return response()->json($readings, 201);
        }
            return response()->json(['message' => 'Glove not found'], 400);


    }

    /**
     * Display the specified resource.
     */
    public function show(Actions $actions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Actions $actions)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Actions $actions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Actions $actions)
    {
        //
    }
}
