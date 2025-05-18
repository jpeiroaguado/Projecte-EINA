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

    $context_actiu = ContexteClasse::where('actiu', true)
        ->where('creat_per', auth()->id())
        ->first();

    return view('panell-professor.index', compact('context_actiu'));
}


    public function edit($id)
    {
        $this->autoritzarProfessor();

        $config = ConfiguracioIA::first();
        $contextos = ContexteClasse::where('creat_per', auth()->id())->get();
        $context_actiu = $contextos->firstWhere('actiu', true);
        $context_seleccionat = $config?->context_id;

        return view('panell-professor.edit', compact('config', 'contextos', 'context_actiu', 'context_seleccionat'));
    }

    public function update(Request $request)
    {
        $this->autoritzarProfessor();

        $request->validate([
            'context_id' => 'required|exists:contexts,id',
        ]);

        $config = ConfiguracioIA::firstOrCreate([], []);
        $config->update([
            'context_id' => $request->input('context_id'),
        ]);

        ContexteClasse::where('creat_per', auth()->id())->update(['actiu' => false]);

        $contextActiu = ContexteClasse::where('id', $request->input('context_id'))->first();
        if ($contextActiu) {
            $contextActiu->update(['actiu' => true]);
        }

        return redirect()->route('configuracio.index')->with('success', 'Context activat correctament.');
    }

    public function activar($id)
    {
        $this->autoritzarProfessor();

        ConfiguracioIA::query()->update(['activa' => false]);

        $config = ConfiguracioIA::findOrFail($id);
        $config->update(['activa' => true]);

        return redirect()->back()->with('success', 'Configuració activada.');
    }
}
