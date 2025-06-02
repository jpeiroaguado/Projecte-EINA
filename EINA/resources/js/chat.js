import Echo from "laravel-echo";
import Pusher from "pusher-js";

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

document.addEventListener("DOMContentLoaded", function () {
    const formulari = document.getElementById("formulari-xat");
    if (!formulari) return;

    const input = document.getElementById("input-missatge");
    const contenedor = document.getElementById("missatges");
    const contextIdActual = parseInt(formulari.dataset.contextId);
    const conversaId = formulari.dataset.conversaId;
    console.log("[chat.js] conversaId:", conversaId);

    // Funció per a escapar LHTML i parsejar format
    function escaparHTML(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
    }
    function parseGeminiFormat(text) {
    // Extraiem i substituïm els blocs ```...``` per marcadors temporals
    const codeBlocks = [];
    text = text.replace(/```(\w*)\n([\s\S]*?)```/g, function (_, lang, code) {
        const placeholder = `__CODE_BLOCK_${codeBlocks.length}__`;
        codeBlocks.push({
            placeholder,
            html: `<pre class="bg-gray-900 text-gray-100 p-4 rounded overflow-x-auto text-sm"><code class="language-${lang}">${escaparHTML(code)}</code></pre>`
        });
        return placeholder;
    });

    // Escapem la resta del contingut (fora dels blocs de codi)
    text = escaparHTML(text);

    // Negretes **text**
    text = text.replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>");

    // Salts de línia
    text = text.replace(/\n/g, "<br>");

    // Tornem a inserir els blocs de codi
    codeBlocks.forEach(cb => {
        text = text.replace(cb.placeholder, cb.html);
    });

    return text;
}


    // Auto-scroll en càrrega
    contenedor.scrollTop = contenedor.scrollHeight;

    //Permet el shift+intro per a saltar linea
    input.addEventListener("keydown", function (e) {
        if (e.key === "Enter" && !e.shiftKey) {
            e.preventDefault();
            formulari.dispatchEvent(new Event("submit", { cancelable: true }));
        }
    });

    // Enviament de missatge
    formulari.addEventListener("submit", function (e) {
        e.preventDefault();
        const missatge = input.value.trim();
        if (!missatge) return;
        const missatgeFormatejat = parseGeminiFormat(missatge);
        contenedor.innerHTML += `
            <div class="text-right">
                <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-2xl inline-block max-w-[90%]">${missatgeFormatejat}</span>
            </div>
        `;
        input.value = "";

        fetch(`/conversa/${conversaId}/missatge`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]')
                    .value,
            },
            body: JSON.stringify({ missatge }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.error) {
                    contenedor.innerHTML += `
                    <div class="text-left text-red-600"> ${data.error}</div>
                `;
                    input.disabled = true;
                    return;
                }

                const respostaFormatejada = parseGeminiFormat(data.resposta);
                contenedor.innerHTML += `
                <div class="text-left">
                    <span class="bg-gray-100 text-gray-800 px-4 py-2 rounded-2xl inline-block max-w-[90%]">${respostaFormatejada}</span>
                </div>
            `;
                document.getElementById("interaccions-restants").innerText =
                    data.interaccions_restants;
                contenedor.scrollTop = contenedor.scrollHeight;
            })
            .catch(() => {
                contenedor.innerHTML += `<div class="text-left text-red-600"> Error en la resposta de la IA</div>`;
            });
    });

    console.log("[chat.js] Subscripció a conversaId =", window.conversaId);

    window.Echo.private(`conversa.${window.conversaId}`)
        .subscribed(() => {
            console.log(
                "[chat.js] Subscripció a conversa." + window.conversaId + " OK"
            );
        })
        .listen("ContextCanviat", (e) => {
            console.log("[chat.js] Rebut ContextCanviat!", e);
            console.log(
                "[chat.js] e.conversa_id =",
                e.conversa_id,
                "window.conversaId =",
                window.conversaId
            );

            // Si la conversa ha canviat, recarreguem totalment la vista
            if (
                e.conversa_id &&
                String(e.conversa_id) !== String(window.conversaId)
            ) {
                console.log("[chat.js] ID de conversa canviat. Recarregant...");
                location.reload();
                return;
            }

            // Si és la mateixa conversa, potser només han canviat les interaccions
            if (e.interaccions_restants !== undefined) {
                const contador = document.getElementById(
                    "interaccions-restants"
                );
                if (contador) {
                    contador.textContent = e.interaccions_restants;
                    console.log(
                        "[chat.js] Interaccions actualitzades:",
                        e.interaccions_restants
                    );
                }
            }
        });
});
