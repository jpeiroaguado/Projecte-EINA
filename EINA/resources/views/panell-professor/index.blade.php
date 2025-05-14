@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-8 space-y-6">
    @foreach ($configuracions as $config)
        <div class="p-6 border rounded-lg shadow-sm @if($config->activa) bg-green-100 @endif">
            @if($config->activa)
                <p class="text-green-700 font-semibold mb-2">🟢 Configuració activa</p>
            @endif

            <p><strong>Màx. interaccions:</strong> {{ $config->max_interaccions }}</p>

            {{-- FUTUR: <p><strong>Context actiu:</strong> {{ $config->context->nom }}</p> --}}

            <form method="POST" action="{{ route('configuracio.activar', $config->id) }}" class="mt-4">
                @csrf
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                    Marcar com activa
                </button>
            </form>

            <a href="{{ route('configuracio.edit', $config->id) }}"
               class="inline-block mt-2 text-blue-600 hover:underline">
                ✏️ Editar
            </a>
        </div>
    @endforeach

    <a href="{{ route('configuracio.edit', 0) }}"
       class="inline-block px-4 py-2 bg-blue-500 text-gray rounded hover:bg-blue-600 transition">
        ➕ Afegir nova configuració
    </a>
</div>
@endsection
