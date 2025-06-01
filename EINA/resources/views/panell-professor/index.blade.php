@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto mt-8 space-y-10">
        {{-- Bloc de configuració activa --}}
        <div class="p-6 border rounded-lg shadow-sm bg-white dark:bg-gray-800 flex justify-between items-start">
            <div>
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Configuració activa</h3>
                @if($context_actiu)
                    <p class="text-green-700 dark:text-green-300 font-semibold mb-2">🟢 Context actiu</p>
                    <p class="text-gray-800 dark:text-gray-200 mb-1">
                        <strong>Nom:</strong> {{ $context_actiu->titol }}
                    </p>
                    <p class="text-gray-800 dark:text-gray-200 mb-1">
                        <strong>Descripció Curta :</strong> {{ $context_actiu->descripcio_curta }}
                    </p>
                    <p class="text-gray-800 dark:text-gray-200 mb-1">
                        <strong>Interaccions màximes:</strong> {{ $context_actiu->interaccions_max }}
                    </p>
                @else
                    <p class="text-gray-500 dark:text-gray-400">No hi ha cap configuració activa.</p>
                @endif

                <a href="{{ route('configuracio.edit', 0) }}"
                   class="inline-block mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    ⚙️ Gestionar configuracions IA
                </a>
            </div>
            @if($context_actiu && $context_actiu->descripcio)
            <div class="max-w-md text-sm text-gray-600 dark:text-gray-300">
                <p class="clamp-8 text-left">
                    {{ $context_actiu->descripcio }}
                </p>
            </div>
            @endif
        </div>

        {{-- Bloc per seleccionar alumne i mini xat --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6" id="contenidor-xat">
            <div class="p-6 border rounded-lg shadow-sm bg-white dark:bg-gray-800">
            <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Selecciona un alumne</h3>
                <ul class="space-y-2">
                    @foreach ($alumnes as $alumne)
                        <li>
                            <button data-id="{{ $alumne->id }}"
                                    class="alumne-btn w-full text-left px-3 py-2 rounded bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-200 dark:hover:bg-gray-600">
                                {{ $alumne->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="md:col-span-2 col-span-3 p-6 border rounded-lg shadow-sm bg-white dark:bg-gray-800 min-h-[300px] transition-all duration-300 ease-in-out" id="panell-xat">
                <div id="xat-alumne" class="overflow-y-auto max-h-[500px] pr-2">
                    <p class="text-gray-500 dark:text-gray-400">Ací es mostrarà el xat de l'alumne en temps real quan estiga implementat.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/panell.js')
@endpush
