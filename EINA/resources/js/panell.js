document.addEventListener("DOMContentLoaded", function () {
    let ID_CONVERSA_SELECCIONADA = null;
    let canalActiu = null;

    function escapeHTML(str) {
        return str.replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;");
    }

    function parseGeminiFormat(text) {
        return text
            .replace(/\*\*(.*?)\*\*/g, "<strong>$1</strong>") // Markdown bold
            .replace(/\n/g, "<br>"); // Line breaks
    }

    // Càrrega AJAX de llistat de converses per alumne
    document.querySelectorAll(".alumne-btn").forEach((btn) => {
        btn.addEventListener("click", function () {
            const id = this.dataset.id;

            fetch(`/panell-professor/conversa/${id}`)
                .then((res) => res.json())
                .then((data) => {
                    const contenedor = document.getElementById("xat-alumne");
                    let html = `<h4 class="font-semibold mb-4 text-gray-800 dark:text-gray-200">🕑 Converses de ${data.alumne}</h4>`;

                    if (data.converses?.length) {
                        html += '<ul class="space-y-3">';
                        data.converses.forEach((c) => {
                            html += `
                            <li>
                                <button class="btn-carrega-conversa text-left w-full border p-3 rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-100 dark:hover:bg-gray-600 transition"
                                        data-id="${c.id}">
                                    <p class="text-sm font-semibold">🕒 ${c.data}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-300 italic">${c.resum_context}</p>
                                </button>
                            </li>`;
                        });
                        html += "</ul>";
                    } else {
                        html += `<p class="text-gray-500 dark:text-gray-400">Aquest alumne no té converses guardades.</p>`;
                    }

                    contenedor.innerHTML = html;
                })
                .catch(() => {
                    document.getElementById(
                        "xat-alumne"
                    ).innerHTML = `<p class="text-red-600">Error al carregar la conversa.</p>`;
                });
        });
    });

    // Carregar conversa per ID (des de botons de converses anteriors)
    document.addEventListener("click", function (e) {
        if (e.target.closest(".btn-carrega-conversa")) {
            const btn = e.target.closest(".btn-carrega-conversa");
            const conversaId = btn.dataset.id;

            fetch(`/panell-professor/conversa-id/${conversaId}`)
                .then((res) => res.json())
                .then((data) => {
                    const container = document.getElementById(
                        "conversa-seleccionada"
                    );
                    const bloc = document.getElementById("contingut-conversa");

                    let html = `<h5 class="text-sm mb-2 text-gray-600 dark:text-gray-300">👤 Alumne: ${data.alumne}</h5>`;

                    data.missatges.forEach((m) => {
                        const align =
                            m.remitent === "alumne"
                                ? "text-right"
                                : "text-left";
                        const estil =
                            m.remitent === "alumne"
                                ? "bg-blue-100 text-blue-800"
                                : "bg-gray-100 text-gray-800";

                        html += `
    <div class="${align}">
        <div class="${estil} px-4 py-2 rounded-2xl inline-block max-w-[90%] mb-4 whitespace-pre-line">
            ${parseGeminiFormat(escapeHTML(m.cos))}
        </div>
    </div>`;
                    });

                    bloc.innerHTML = html;
                    container.classList.remove("hidden");
                    container.scrollIntoView({ behavior: "smooth" });
                });
        }
    });
});
