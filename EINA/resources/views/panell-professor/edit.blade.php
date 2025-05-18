@extends('layouts.app')

@section('content')


<div class="max-w-4xl mx-auto p-6 dark:bg-gray-900 bg-white shadow-md rounded-lg space-y-8 text-gray-900 dark:text-gray-100">

    <h2 class="text-2xl font-bold">⚙️ Panell de configuració</h2>

    {{-- 🔔 Missatges de sessió --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-200 dark:bg-green-700 border border-green-500 text-green-900 dark:text-white rounded">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-4 p-4 bg-red-200 dark:bg-red-700 border border-red-500 text-red-900 dark:text-white rounded">
            {{ session('error') }}
        </div>
    @endif

    {{-- ⚠️ Missatge si no hi ha contextos --}}
    @if ($contextos->isEmpty())
        <div class="mb-4 p-4 border border-yellow-400 bg-yellow-100 dark:bg-yellow-800 text-yellow-700 dark:text-yellow-100 rounded">
            ⚠️ Encara no has creat cap context. Afegeix-ne un per a poder configurar la IA.
        </div>
    @endif

    {{-- Botó per mostrar formulari de nou context --}}
    <div class="mt-6" id="botons-toggle">
        <button onclick="mostrarFormulariContext()"
                class="flex items-center gap-2 text-sm text-white bg-blue-600 px-3 py-2 rounded hover:bg-blue-700">
            ➕ Afegir nou context
        </button>
    </div>

    {{-- Formulari nou context ocult per defecte --}}
    <form method="POST" action="{{ route('contextes.store') }}" id="nou-context-form" class="hidden">
        @csrf
        <input type="hidden" name="context_id" id="context_edit_id" value="">
        <div class="mt-4">
            <textarea name="titol" id="context_edit_titol" rows="1" placeholder="Títol del context"
                      class="w-full border rounded px-3 py-2 text-black dark:text-white dark:bg-gray-800">{{ old('titol') }}</textarea>
        </div>
        <div class="mt-2">
            <textarea name="descripcio" id="context_edit_descripcio" rows="4" placeholder="Descripció (opcional)"
                      class="w-full border rounded px-3 py-2 text-black dark:text-white dark:bg-gray-800">{{ old('descripcio') }}</textarea>
        </div>
        <div class="mt-2">
            <label for="interaccions_max" class="block font-semibold mb-1 dark:text-white">Màx. interaccions</label>
            <input type="number" name="interaccions_max" id="interaccions_max" min="1"
                   class="w-full border rounded px-3 py-2 text-black dark:text-white dark:bg-gray-800"
                   value="{{ old('interaccions_max') }}">
        </div>
        <div class="mt-4 text-right">
            <button type="submit" id="context-form-submit"
                    class="px-5 py-2 bg-gray-700 text-white rounded hover:bg-gray-800 transition">
                💾 Guardar context
            </button>
        </div>
    </form>

    @if (!$contextos->isEmpty())
        {{-- Llistat de contextos --}}
        <div class="mt-6">
            <h3 class="font-semibold mb-2">📄 Llista de contextos</h3>

            @foreach ($contextos as $context)
                <div class="border rounded p-4 mb-2 flex justify-between items-center
                    @if($context->actiu) border-2 border-blue-600 bg-blue-50 dark:bg-blue-900 @endif">
                    <div>
                        <p class="font-semibold">{{ $context->titol }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">{{ $context->descripcio }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">🔢 Interaccions màximes: {{ $context->interaccions_max }}</p>
                        @if($context->actiu)
                            <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">🟢 Context actiu per al programa</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Botó activar --}}
                        <form method="POST" action="{{ route('contextes.activate', $context->id) }}">
                            @csrf
                            <button type="submit"
                                    class="px-3 py-1 text-sm rounded bg-green-500 text-white hover:bg-green-600">
                                Activar
                            </button>
                        </form>

                        {{-- Botó editar --}}
                        <button type="button"
                                onclick="editarContext('{{ $context->id }}', '{{ addslashes($context->titol) }}', '{{ addslashes($context->descripcio) }}', '{{ $context->interaccions_max }}')"
                                class="px-3 py-1 text-sm rounded bg-yellow-400 text-white hover:bg-yellow-500">
                            Editar
                        </button>

                        {{-- Botó eliminar --}}
                        <form method="POST" action="{{ route('contextes.destroy', $context->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-3 py-1 text-sm rounded bg-red-500 text-white hover:bg-red-600"
                                    onclick="return confirm('Vols eliminar aquest context?')">
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
<a href="{{ route('configuracio.index') }}"
   class="block w-fit mx-auto mt-6 px-6 py-3 text-lg font-semibold bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-100 rounded hover:bg-gray-300 dark:hover:bg-gray-700 transition">
    ⬅️ Tornar al panell
</a>
@endsection
