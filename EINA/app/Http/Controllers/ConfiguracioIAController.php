<?php

namespace App\Http\Controllers;

use App\Models\ContextClasse;
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

        $context_actiu = ContextClasse::where('actiu', true)
            ->where('creat_per', auth()->id())
            ->first();

        return view('panell-professor.index', compact('context_actiu'));
    }

    public function edit($id)
    {
        $this->autoritzarProfessor();

        $contextos = ContextClasse::where('creat_per', auth()->id())->get();
        $context_actiu = $contextos->firstWhere('actiu', true);
        $context_seleccionat = $context_actiu?->id;

        return view('panell-professor.edit', compact('contextos', 'context_actiu', 'context_seleccionat'));
    }

    public function update(Request $request, $id)
    {
        $this->autoritzarProfessor();

        $request->validate([
            'context_id' => 'required|exists:contexts,id',
        ]);

        // Desactivar tots els contextos de l’usuari
        ContextClasse::where('creat_per', auth()->id())->update(['actiu' => false]);

        // Activar el context seleccionat
        $contextActiu = ContextClasse::where('id', $request->input('context_id'))->first();
        if ($contextActiu) {
            $contextActiu->update(['actiu' => true]);
        }

        return redirect()->route('configuracio.index')->with('success', 'Context activat correctament.');
    }
}
