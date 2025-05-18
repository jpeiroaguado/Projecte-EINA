window.mostrarFormulariContext = function () {
    const form = document.getElementById('nou-context-form');
    form.classList.toggle('hidden');

    // Reset del formulari
    document.getElementById('context-form-submit').innerText = '💾 Guardar context';
    document.getElementById('context_edit_id').value = '';
    document.getElementById('context_edit_titol').value = '';
    document.getElementById('context_edit_descripcio').value = '';

    // Mostrar el botó de toggle (per si estava ocult per editar)
    const toggle = document.getElementById('botons-toggle');
    if (toggle) {
        toggle.classList.remove('hidden');
    }

    // Reinicia el formulari per a crear (no editar)
    const formElement = document.getElementById('nou-context-form');
    formElement.action = '/contextes';
    // Elimina qualsevol input hidden _method
    const methodInput = formElement.querySelector('input[name="_method"]');
    if (methodInput) methodInput.remove();
};
window.editarContext = function (id, titol, descripcio, interaccions) {
    document.getElementById('context_edit_id').value = id;
    document.getElementById('context_edit_titol').value = titol;
    document.getElementById('context_edit_descripcio').value = descripcio;
    document.getElementById('interaccions_max').value = interaccions;

    document.getElementById('nou-context-form').classList.remove('hidden');

    const boto = document.getElementById('context-form-submit');
    boto.innerText = '💾 Actualitzar context';
    boto.onclick = function () {
        const form = document.getElementById('nou-context-form');
        form.action = `/contextes/${id}`;
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        form.appendChild(methodInput);
        form.submit();
    };

    document.getElementById('botons-toggle').classList.add('hidden');
};
