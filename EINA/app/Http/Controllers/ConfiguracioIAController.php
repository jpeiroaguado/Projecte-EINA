<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracioIA;
use App\Models\ContexteClasse;
use Illuminate\Http\Request;

class ConfiguracioIAController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function autoritzarProfessor()
    {
        if (auth()->user()->rol !== 'professor') {
            abort(403, 'Accés només per a professors');
        }
    }

    public function index()
    {
        $this->autoritzarProfessor();

        $configuracions = ConfiguracioIA::all();
        return view('panell-professor.index', compact('configuracions'));
    }

    public function activar($id)
    {
        ConfiguracioIA::query()->update(['activa' => false]);

        $config = ConfiguracioIA::findOrFail($id);
        $config->update(['activa' => true]);

        return redirect()->back()->with('success', 'Configuració activada');
    }

    public function create()
    {
        $this->autoritzarProfessor();

        $contextos = ContexteClasse::where('creat_per', auth()->id())->get();
        $config = new ConfiguracioIA();

        return view('panell-professor.edit', compact('config', 'contextos'));
    }

    public function store(Request $request)
    {
        $this->autoritzarProfessor();

        $request->validate([
            'max_interaccions' => 'required|integer|min:1',
            'context_id' => 'required|exists:contexts,id',
        ]);

        ConfiguracioIA::create([
            'max_interaccions' => $request->input('max_interaccions'),
            'context_id' => $request->input('context_id'),
            'activa' => false,
        ]);

        return redirect()->route('configuracio.index')->with('success', 'Configuració creada.');
    }

    public function edit($id)
    {
        $this->autoritzarProfessor();

        $config = $id == 0 ? new ConfiguracioIA() : ConfiguracioIA::findOrFail($id);
        $contextos = ContexteClasse::where('creat_per', auth()->id())->get();

        return view('panell-professor.edit', compact('config', 'contextos'));
    }

    public function update(Request $request)
    {
        $this->autoritzarProfessor();

        $request->validate([
            'max_interaccions' => 'required|integer|min:1',
            'context_id' => 'required|exists:contexts,id',
        ]);

        $config = ConfiguracioIA::first(); // Podria ficar findOrFail($id) si passes l'ID
        $config->update([
            'max_interaccions' => $request->input('max_interaccions'),
            'context_id' => $request->input('context_id'),
        ]);

        return redirect()->route('configuracio.index')->with('success', 'Configuració actualitzada.');
    }

    public function destroy(string $id)
    {
        //
    }
}
