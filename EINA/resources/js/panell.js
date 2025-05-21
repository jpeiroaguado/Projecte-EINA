document.addEventListener('DOMContentLoaded', function () {
    let ID_CONVERSA_SELECCIONADA = null;

    // Carreguem conversa per AJAX quan es fa clic a un alumne
    document.querySelectorAll('.alumne-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;

            fetch(`/panell-professor/conversa/${id}`)
                .then(res => res.json())
                .then(data => {
                    ID_CONVERSA_SELECCIONADA = data.conversa_id;
                    const contenedor = document.getElementById('xat-alumne');
                    let html = `<h4 class="font-semibold mb-2">💬 Xat amb ${data.alumne}</h4>`;
                    data.missatges.forEach(m => {
                        const align = m.remitent === 'alumne' ? 'text-right' : 'text-left';
                        const estil = m.remitent === 'alumne'
                            ? 'bg-blue-100 text-blue-800'
                            : 'bg-gray-100 text-gray-800';

                        html += `
                            <div class="${align}">
                                <span class="${estil} px-3 py-2 rounded inline-block max-w-[90%] mb-2">${m.cos.replace(/\n/g, '<br>')}</span>
                            </div>`;
                    });

                    contenedor.innerHTML = html;

                    // Subscripció en temps real a la conversa
                    if (window.Echo && ID_CONVERSA_SELECCIONADA) {
                        window.Echo.channel('xat-alumne.' + ID_CONVERSA_SELECCIONADA)
                            .listen('.nou-missatge', (e) => {
                                contenedor.innerHTML += `
                                    <div class="text-left">
                                        <span class="bg-gray-100 text-gray-800 px-3 py-2 rounded inline-block max-w-[90%] mb-2">${e.missatge.cos}</span>
                                    </div>`;
                            });
                    }
                })
                .catch(() => {
                    const contenedor = document.getElementById('xat-alumne');
                    contenedor.innerHTML = `<p class="text-red-600">Error al carregar la conversa.</p>`;
                });
        });
    });

    // Botons maximitzar i minimitzar
    const panell = document.querySelector('.md\\:col-span-2');
    const btnMax = document.getElementById('btn-maximitza');
    const btnMin = document.getElementById('btn-minimitza');

    if (btnMax && btnMin && panell) {
        btnMax.addEventListener('click', () => {
            panell.classList.remove('md:col-span-2');
            panell.classList.add('col-span-3');
            btnMax.classList.add('hidden');
            btnMin.classList.remove('hidden');
        });

        btnMin.addEventListener('click', () => {
            panell.classList.remove('col-span-3');
            panell.classList.add('md:col-span-2');
            btnMax.classList.remove('hidden');
            btnMin.classList.add('hidden');
        });
    }
});
