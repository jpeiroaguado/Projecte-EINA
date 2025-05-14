<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContexteClasse;

class ContextController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $contextos = ContexteClasse::all();
    return view('context.index', compact('contextos'));
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
        'nom' => 'required|string|max:255',
    ]);

    ContexteClasse::create($request->all());
    return redirect()->back()->with('success', 'Context afegit');
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
