document.addEventListener('DOMContentLoaded', function () {
    const formulari = document.getElementById('formulari-xat');
    if (!formulari) return;

    const input = document.getElementById('input-missatge');
    const contenedor = document.getElementById('missatges');
    const contextIdActual = parseInt(formulari.dataset.contextId);
    const conversaId = formulari.dataset.conversaId;

    // Auto-scroll en càrrega
    contenedor.scrollTop = contenedor.scrollHeight;

    // Enviament de missatge
    formulari.addEventListener('submit', function (e) {
        e.preventDefault();
        const missatge = input.value.trim();
        if (!missatge) return;

        contenedor.innerHTML += `
            <div class="text-right">
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-2xl inline-block max-w-[90%]">${missatge}</span>
            </div>
        `;
        input.value = '';

        fetch(`/conversa/${conversaId}/missatge`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: JSON.stringify({ missatge })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                contenedor.innerHTML += `
                    <div class="text-left text-red-600"> ${data.error}</div>
                `;
                input.disabled = true;
                return;
            }

            contenedor.innerHTML += `
                <div class="text-left">
                    <span class="bg-gray-100 text-gray-800 px-4 py-2 rounded-2xl inline-block max-w-[90%]">${data.resposta}</span>
                </div>
            `;
            document.getElementById('interaccions-restants').innerText = data.interaccions_restants;
            contenedor.scrollTop = contenedor.scrollHeight;
        })
        .catch(() => {
            contenedor.innerHTML += `<div class="text-left text-red-600"> Error en la resposta de la IA</div>`;
        });
    });

    // Comprove canvi de context cada 4 segons
    setInterval(() => {
        fetch('/api/context-actiu')
            .then(res => res.json())
            .then(data => {
                if (data.id && data.id !== contextIdActual) {
                    location.reload();
                }
            });
    }, 4000);
});
