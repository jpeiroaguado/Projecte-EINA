<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContextClasse;
use Illuminate\Support\Facades\Log;


class ContextController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (auth()->user()->rol !== 'professor') {
                abort(403, 'Accés només per a professors');
            }
            return $next($request);
        })->except('show');
    }

    public function create()
    {
        return view('contextes.create');
    }
    public function index()
    {
        $this->autoritzarProfessor();

        $context_actiu = ContextClasse::where('actiu', true)
            ->where('creat_per', auth()->id())
            ->first();

        return view('panell-professor.index', compact('context_actiu'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'titol' => 'required|string|max:255',
            'descripcio' => 'nullable|string',
            'interaccions_max' => 'nullable|integer|min:1'
        ]);

        ContextClasse::create([
            'titol' => $request->input('titol'),
            'descripcio' => $request->input('descripcio'),
            'interaccions_max' => $request->input('interaccions_max', 10),
            'actiu' => false,
            'creat_per' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Context creat exitosament');
    }

    public function show($id)
    {
        $context = ContextClasse::findOrFail($id);
        return view('contextes.show', compact('context'));
    }

    public function edit($id)
    {
        $context = ContextClasse::findOrFail($id);

        if ($context->creat_per !== auth()->id()) {
            abort(403);
        }

        return view('contextes.edit', compact('context'));
    }

    public function update(Request $request, $id)
    {
        $context = ContextClasse::findOrFail($id);

        if ($context->creat_per !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'titol' => 'required|string|max:255',
            'descripcio' => 'nullable|string',
            'interaccions_max' => 'nullable|integer|min:1'
        ]);

        $context->update([
            'titol' => $request->input('titol'),
            'descripcio' => $request->input('descripcio'),
            'interaccions_max' => $request->input('interaccions_max', 10),
        ]);

        return redirect()->back()->with('success', 'Context actualitzat.');
    }

    public function activate($id)
{
    $usuariId = auth()->id();

    // Desactiva contextos anteriors
    ContextClasse::where('creat_per', $usuariId)->update(['actiu' => false]);

    // Activa el nou context
    $context = ContextClasse::where('creat_per', $usuariId)->findOrFail($id);
    $context->update(['actiu' => true]);

    Log::info("[activate] Activant context ID: {$context->id} per usuari {$usuariId}");

    // Agafem totes les converses
    $converses = \App\Models\Conversa::all();

    foreach ($converses as $conversaAntiga) {
        if ($conversaAntiga->context_id != $context->id) {
            // Crear nova conversa
            $novaConversa = new \App\Models\Conversa();
            $novaConversa->usuari_id = $conversaAntiga->usuari_id;
            $novaConversa->context_id = $context->id;
            $novaConversa->interaccions_restants = $context->interaccions_max ?? 10;
            $novaConversa->save();

            // Carrega la relació context abans de l’event
            $novaConversa->load('context');

            Log::info("[activate] Nova conversa creada per a usuari {$novaConversa->usuari_id} amb context {$context->id}");

            try {
                broadcast(new \App\Events\ContextCanviat($novaConversa));
                Log::info("[activate] Event ContextCanviat emés per conversa ID: {$novaConversa->id}");
            } catch (\Throwable $e) {
                Log::error("[activate] ERROR al broadcast: " . $e->getMessage());
                Log::error($e->getTraceAsString());
            }

        } else {
            Log::info("[activate] Conversa ID {$conversaAntiga->id} ja tenia el context actiu. No s’ha fet res.");
        }
    }

    return redirect()->back()->with('success', 'Context activat i aplicat a totes les converses.');
}








    public function destroy(string $id)
    {
        $context = ContextClasse::findOrFail($id);

        if ($context->creat_per !== auth()->id()) {
            abort(403);
        }

        if ($context->actiu) {
            return redirect()->back()->with('error', 'No pots eliminar el context actiu. Activa un altre abans.');
        }

        $context->delete();

        return redirect()->back()->with('success', 'Context eliminat correctament.');
    }
}
