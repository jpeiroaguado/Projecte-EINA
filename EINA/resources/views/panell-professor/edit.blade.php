@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">

    <h2 class="text-2xl font-bold mb-6">
        {{ isset($config) && $config->id ? '✏️ Editar configuració' : '➕ Afegir nova configuració' }}
    </h2>

    <form method="POST" action="{{ isset($config) && $config->id ? route('configuracio.update', $config->id) : route('configuracio.store') }}">
        @csrf
        @if(isset($config) && $config->id)
            @method('PUT')
        @endif

        <!-- 🔽 Seleccionar context -->
        <div class="mb-4">
            <label for="context_id" class="block font-semibold mb-1">Context de la configuració</label>
            <select name="context_id" id="context_id"
                    class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                <option value="" disabled {{ old('context_id', $config->context_id ?? '') ? '' : 'selected' }}>
                    — Selecciona un context —
                </option>
                @foreach($contextos as $context)
                    <option value="{{ $context->id }}"
                        {{ old('context_id', $config->context_id ?? '') == $context->id ? 'selected' : '' }}>
                        {{ $context->titol }}
                    </option>
                @endforeach
            </select>
            @error('context_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            <!-- ➕ Afegir context nou -->
            <a href="{{ route('contextes.create') }}" class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                ➕ Crear un nou context
            </a>
        </div>

        <!-- 🔢 Màx. interaccions -->
        <div class="mb-4">
            <label for="max_interaccions" class="block font-semibold mb-1">Màx. interaccions</label>
            <input type="number" name="max_interaccions" id="max_interaccions"
                   class="w-full border-gray-300 rounded shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                   value="{{ old('max_interaccions', $config->max_interaccions ?? '') }}" required min="1">
            @error('max_interaccions')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- ⚡ Botons -->
        <div class="mt-6 flex flex-col sm:flex-row justify-between gap-4 items-center">
            <a href="{{ route('configuracio.index') }}" class="text-gray-600 hover:underline">
                ⬅ Tornar al panell
            </a>

            <div class="flex gap-2 items-center">
                @if(isset($config) && $config->id && !$config->activa)
                    <form method="POST" action="{{ route('configuracio.activar', $config->id) }}">
                        @csrf
                        <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                            ✅ Activar configuració
                        </button>
                    </form>
                @endif

                <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    💾 Guardar
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
