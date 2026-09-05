document.addEventListener('invalid', function (event) {
    const campo = event.target;
    if (!(campo instanceof HTMLInputElement) && !(campo instanceof HTMLTextAreaElement) && !(campo instanceof HTMLSelectElement)) {
        return;
    }

    event.preventDefault();
    campo.classList.add('border-red-400');

    let mensagem = campo.nextElementSibling;
    if (!mensagem || !mensagem.classList.contains('erro-validacao-cliente')) {
        mensagem = document.createElement('p');
        mensagem.className = 'erro-validacao-cliente text-red-500 text-xs mt-1.5';
        campo.insertAdjacentElement('afterend', mensagem);
    }

    if (campo.validity.valueMissing) {
        mensagem.textContent = 'Esse campo é obrigatório.';
    } else if (campo.validity.typeMismatch && campo.type === 'email') {
        mensagem.textContent = 'Confira o e-mail digitado — parece estar incompleto (falta o @, por exemplo).';
    } else {
        mensagem.textContent = 'Confira esse campo antes de continuar.';
    }

    campo.focus();
}, true);

document.addEventListener('input', function (event) {
    const campo = event.target;
    campo.classList.remove('border-red-400');

    const mensagem = campo.nextElementSibling;
    if (mensagem && mensagem.classList.contains('erro-validacao-cliente')) {
        mensagem.remove();
    }
}, true);
