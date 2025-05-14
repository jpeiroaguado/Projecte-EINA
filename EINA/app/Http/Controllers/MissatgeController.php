<?php

namespace App\Http\Controllers;

use App\Models\Missatge;
use Illuminate\Http\Request;

class MissatgeController extends Controller
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
    $request->validate([
        'conversa_id' => 'required|exists:converses,id',
        'emissor' => 'required|string',
        'contingut' => 'required|string',
    ]);

    Missatge::create($request->all());
    return response()->json(['status' => 'Missatge guardat']);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
