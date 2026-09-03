<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-extrabold text-2xl text-graphite">Imagens do site</h1>
        <p class="text-sm text-graphite/60 mt-1">Troque a capa da home, da galeria e as fotos da página Sobre. A troca aparece no site na hora.</p>
    </x-slot>

    @if ($erro)
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">{{ $erro }}</div>
    @endif

    @if (session('erro'))
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">{{ session('erro') }}</div>
    @endif

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($imagens as $imagem)
            <section class="rounded-xl border border-graphite/10 bg-white p-5">
                <div class="rounded-md overflow-hidden bg-graphite-light/10 mb-4" style="aspect-ratio: {{ str_replace(':', ' / ', $imagem['proporcao'] ?? '16:9') }};">
                    @if ($imagem['url'])
                        <img src="{{ $imagem['url'] }}" alt="" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-xs text-graphite/40">Sem imagem</div>
                    @endif
                </div>

                <h2 class="font-display font-bold text-base text-graphite mb-1">{{ $imagem['label'] }}</h2>
                <p class="text-xs text-graphite/50 mb-2">{{ $imagem['definida'] ? 'Definida pelo painel' : 'Usando imagem padrão do site' }}</p>
                @if (!empty($imagem['dica']))
                    <p class="text-xs text-graphite/40 mb-4">📐 {{ $imagem['dica'] }}</p>
                @endif

                <form method="POST" action="{{ route('site-images.update', $imagem['slot']) }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                    @csrf
                    <input type="file" name="imagem" accept="image/*" required
                           class="text-xs text-graphite/70 file:mr-2 file:rounded-md file:border-0 file:bg-graphite/5 file:px-3 file:py-2 file:text-xs file:font-medium hover:file:bg-graphite/10 w-full">
                    <button type="submit" class="text-xs font-medium rounded-md bg-terracotta text-cream px-4 py-2 hover:bg-terracotta-dark transition">Trocar imagem</button>
                </form>
            </section>
        @endforeach
    </div>
</x-app-layout>
