<?php

namespace App\Http\Controllers;

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
