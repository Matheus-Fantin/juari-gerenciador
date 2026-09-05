document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('submit', function (event) {
        const form = event.target;
        if (!(form instanceof HTMLFormElement)) return;

        const botao = form.querySelector('button[type="submit"]');
        if (!botao || botao.disabled) return;

        botao.dataset.textoOriginal = botao.innerHTML;
        botao.disabled = true;
        botao.classList.add('opacity-70', 'cursor-wait');
        botao.innerHTML = `
            <span class="inline-flex items-center gap-2">
                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                Carregando...
            </span>
        `;

        // Se o servidor demorar demais pra responder (ex: acordando de "sleep"),
        // libera o botao de novo depois de um tempo para o usuario poder tentar de novo.
        setTimeout(function () {
            if (botao.isConnected && botao.disabled) {
                botao.disabled = false;
                botao.classList.remove('opacity-70', 'cursor-wait');
                botao.innerHTML = botao.dataset.textoOriginal;
            }
        }, 30000);
    });
});
