import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

function parseAspect(value) {
    if (!value) return NaN;
    const [w, h] = value.split(/[:/]/).map(Number);
    return w && h ? w / h : NaN;
}

function buildModal() {
    const overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 z-50 bg-graphite/70 flex items-center justify-center p-4';
    overlay.hidden = true;
    overlay.innerHTML = `
        <div class="bg-white rounded-xl w-full max-w-lg overflow-hidden shadow-xl">
            <div class="px-5 py-4 border-b border-graphite/10">
                <h2 class="font-display font-bold text-base text-graphite">Ajustar o corte da foto</h2>
                <p class="text-xs text-graphite/50 mt-0.5">Arraste a foto pra reposicionar, ou arraste as bordas do quadro pra ajustar o quanto aparece. Use a roda do mouse (ou pince na tela) pra dar zoom.</p>
            </div>
            <div class="bg-graphite-light/10" style="max-height:60vh;overflow:hidden;">
                <img class="crop-target block max-w-full">
            </div>
            <div class="px-5 py-4 flex items-center justify-end gap-3">
                <button type="button" class="crop-cancelar text-sm font-medium rounded-md border border-graphite/15 px-4 py-2 hover:bg-graphite/5 transition">Cancelar</button>
                <button type="button" class="crop-usar text-sm font-medium rounded-md bg-terracotta text-cream px-4 py-2 hover:bg-terracotta-dark transition">Usar este corte</button>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
    return overlay;
}

document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[type="file"][data-crop-aspect]');
    if (!inputs.length) return;

    const overlay = buildModal();
    const imgEl = overlay.querySelector('.crop-target');
    const btnUsar = overlay.querySelector('.crop-usar');
    const btnCancelar = overlay.querySelector('.crop-cancelar');

    let cropper = null;
    let inputAtivo = null;
    let nomeArquivoOriginal = 'foto.jpg';

    function fechar() {
        overlay.hidden = true;
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
    }

    inputs.forEach((input) => {
        input.addEventListener('change', function () {
            const file = input.files && input.files[0];
            if (!file) return;

            inputAtivo = input;
            nomeArquivoOriginal = file.name.replace(/\.[^.]+$/, '') + '.jpg';

            const leitor = new FileReader();
            leitor.onload = function (e) {
                imgEl.src = e.target.result;
                overlay.hidden = false;

                if (cropper) cropper.destroy();
                cropper = new Cropper(imgEl, {
                    aspectRatio: parseAspect(input.dataset.cropAspect),
                    viewMode: 1,
                    dragMode: 'move',
                    autoCropArea: 1,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                    background: false,
                    zoom(event) {
                        // Não deixa ampliar além do tamanho real da foto (evita ficar borrada/pixelada).
                        if (event.detail.ratio > 1) {
                            event.preventDefault();
                        }
                    },
                });
            };
            leitor.readAsDataURL(file);
        });
    });

    btnCancelar.addEventListener('click', function () {
        if (inputAtivo) inputAtivo.value = '';
        fechar();
    });

    btnUsar.addEventListener('click', function () {
        if (!cropper || !inputAtivo) return;

        cropper.getCroppedCanvas({
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }).toBlob(function (blob) {
            const arquivoCortado = new File([blob], nomeArquivoOriginal, { type: 'image/jpeg' });
            const dt = new DataTransfer();
            dt.items.add(arquivoCortado);
            inputAtivo.files = dt.files;
            fechar();
        }, 'image/jpeg', 0.92);
    });
});
