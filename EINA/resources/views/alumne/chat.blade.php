@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto mt-10 px-4">
    <h2 class="text-xl font-bold mb-4 text-white">💬 Xat amb la IA</h2>

    <div id="missatges" class="space-y-4 bg-white p-4 rounded shadow mb-6 h-96 overflow-y-auto">
        <!-- Ací s'aniran afegint els missatges -->
    </div>

    <form id="formulari-xat" class="flex gap-4">
        @csrf
        <input type="text" id="input-missatge" class="flex-grow border rounded px-4 py-2 text-black"
               placeholder="Escriu el teu missatge..." required>
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Enviar
        </button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const formulari = document.getElementById('formulari-xat');
    const input = document.getElementById('input-missatge');
    const contenedor = document.getElementById('missatges');

    formulari.addEventListener('submit', function (e) {
        e.preventDefault();
        const missatge = input.value.trim();
        if (!missatge) return;

        // Mostrar missatge de l'alumne
        contenedor.innerHTML += `<div class="text-right"><span class="bg-blue-100 text-blue-800 px-3 py-2 rounded inline-block">${missatge}</span></div>`;
        input.value = '';

        fetch('{{ route('conversa.enviar', 1) }}', { // ← pots substituir 1 pel valor real de la conversa
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ missatge: missatge })
        })
        .then(res => res.json())
        .then(data => {
            contenedor.innerHTML += `<div class="text-left"><span class="bg-gray-100 text-gray-800 px-3 py-2 rounded inline-block">${data.resposta}</span></div>`;
            contenedor.scrollTop = contenedor.scrollHeight;
        })
        .catch(err => {
            contenedor.innerHTML += `<div class="text-left text-red-600">⚠️ Error en la resposta de la IA</div>`;
        });
    });
});
</script>
@endsection
