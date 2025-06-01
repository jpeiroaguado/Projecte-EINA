<?php

namespace App\Http\Controllers;

use App\Events\ContextCanviat;
use App\Models\Conversa;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ContextClasse;


class ConversaController extends Controller
{
    private function autoritzarProfessor()
    {
        if (auth()->user()->rol !== 'professor') {
            abort(403, 'No tens permís per accedir a esta secció.');
        }
    }
    public function index()
    {
        $converses = Conversa::with('usuari')->get();
        return view('converses.index', compact('converses'));
    }

    public function xatAmbIA()
    {
        $usuari = auth()->user();
        $contextActiu = \App\Models\ContextClasse::where('actiu', true)->first();

        if (!$contextActiu) {
            abort(500, 'No hi ha cap context actiu.');
        }

        $conversa = Conversa::where('usuari_id', $usuari->id)
            ->latest()
            ->first();

        // Reinicie si el context ha canviat
        if (!$conversa || $conversa->context_id !== $contextActiu->id) {
            $conversa = Conversa::create([
                'usuari_id' => $usuari->id,
                'context_id' => $contextActiu->id,
                'interaccions_restants' => $contextActiu->interaccions_max,
            ]);
        }

        return view('alumne.chat', [
            'conversaId' => $conversa->id,
            'conversa' => $conversa,
        ]);
    }
    public function actualitzarContext(Request $request, Conversa $conversa)
    {
        $request->validate([
            'context_id' => 'required|exists:contexts,id',
        ]);

        $nouContext = ContextClasse::findOrFail($request->context_id);

        $conversa->context_id = $nouContext->id;
        $conversa->interaccions_restants = $nouContext->interaccions_max ?? 10;
        $conversa->save();

        event(new \App\Events\ContextCanviat($conversa));

        return response()->json(['success' => true]);
    }

    public function mostrarConversaPerUsuari($id)
{
    $alumne = User::findOrFail($id);

    $conversaActiva = Conversa::where('usuari_id', $id)
        ->latest()
        ->first();

    $missatges = $conversaActiva
        ? $conversaActiva->missatges()->orderBy('created_at')->get()
        : collect();

    $converses = Conversa::where('usuari_id', $id)
        ->with(['missatges', 'context']) // afegim el context
        ->orderByDesc('created_at')
        ->get()
        ->map(function ($conversa) {
            return [
                'id' => $conversa->id,
                'data' => optional($conversa->missatges->first())->created_at?->format('d/m/Y H:i') ?? 'Sense missatges',
                'resum_context' => $conversa->context->descripcio_curta ?? 'Sense context'
            ];
        });

    return response()->json([
        'alumne' => $alumne->name,
        'conversa_id' => $conversaActiva?->id,
        'missatges' => $missatges,
        'converses' => $converses,
    ]);
}


    public function mostrarConversaPerId($id)
    {
        $conversa = Conversa::with('missatges', 'usuari')->findOrFail($id);

        return response()->json([
            'alumne' => $conversa->usuari->name,
            'missatges' => $conversa->missatges,
        ]);
    }

    public function create() {}

    public function veureConversesAlumne($id)
    {
        $alumne = User::where('rol', 'alumne')->findOrFail($id);
        $converses = Conversa::where('usuari_id', $alumne->id)->with('context')->latest()->get();

        return view('professor.converses.alumne', compact('alumne', 'converses'));
    }

    public function store(Request $request) {}

    public function show($id)
    {
        $conversa = Conversa::with('missatges')->findOrFail($id);
        return view('converses.show', compact('conversa'));
    }

    //Per a vore converses al panell de professor
    public function panell()
    {
        $this->autoritzarProfessor(); // assegura’t que tens esta funció definida

        $context_actiu = ContextClasse::where('actiu', true)
            ->where('creat_per', auth()->id())
            ->first();

        $alumnes = User::where('rol', 'alumne')->get();

        return view('panell-professor.index', compact('context_actiu', 'alumnes'));
    }
    public function carregarPerProfessor($id)
    {
        $alumne = User::findOrFail($id);
        $conversa = Conversa::where('usuari_id', $alumne->id)
            ->with('missatges')
            ->latest()
            ->first();

        return response()->json([
            'alumne' => $alumne->name,
            'missatges' => $conversa?->missatges ?? [],
        ]);
    }

    public function edit(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
