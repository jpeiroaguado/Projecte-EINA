@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto mt-10 px-4">
        <div class="flex justify-between items-center mb-4">
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline w-5 h-5 me-1 text-indigo-600 dark:text-indigo-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 8h10M7 12h6m-6 4h6m1 4l-1-4H7a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-3l-1 4z" />
                    </svg>
                    Xat amb la IA
                </h2>
                @if ($conversa->context && $conversa->context->descripcio_curta)
                    <p id="context-descripcio" class="text-sm text-gray-600 dark:text-gray-300 italic">
                        {{ $conversa->context->descripcio_curta }}
                    </p>

                @endif
            </div>
            <div class="bg-gray-200 text-gray-800 px-3 py-1 rounded text-sm font-semibold">
                Interaccions restants: <span id="interaccions-restants">{{ $conversa->interaccions_restants }}</span>
            </div>
        </div>

        <div id="missatges" class="space-y-4 bg-white p-4 rounded shadow mb-6 overflow-y-auto min-h-[300px]">
            @foreach ($conversa->missatges as $missatge)
                <div class="{{ $missatge->remitent === 'alumne' ? 'text-right' : 'text-left' }}">
                    <span
                        class="{{ $missatge->remitent === 'alumne' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} px-4 py-2 rounded-2xl inline-block max-w-[90%]">
                        {!! nl2br(\Illuminate\Support\Str::markdown($missatge->cos)) !!}
                    </span>
                </div>
            @endforeach
        </div>

        <form id="formulari-xat"
              class="flex gap-4"
              data-conversa-id="{{ $conversaId }}"
              data-context-id="{{ $conversa->context->id }}">
            @csrf
            <input type="text" id="input-missatge" class="flex-grow border rounded px-4 py-2 text-black"
                placeholder="Escriu el teu missatge..." required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                Enviar
            </button>
        </form>
    </div>
@endsection
<script>
    window.conversaId = {{ $conversa->id }};
</script>

@push('scripts')
    @vite('resources/js/chat.js')
@endpush
