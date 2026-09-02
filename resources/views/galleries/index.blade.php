<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-extrabold text-2xl text-graphite">Galeria</h1>
        <p class="text-sm text-graphite/60 mt-1">Adicione ou exclua fotos por categoria. As mudanças aparecem no site na hora.</p>
    </x-slot>

    @if ($erro)
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">{{ $erro }}</div>
    @endif

    @if (session('erro'))
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">{{ session('erro') }}</div>
    @endif

    @foreach ($grupos as $grupo)
        @if (($grupo['galerias'] ?? collect())->isNotEmpty())
            <div class="mb-4 mt-10 first:mt-0">
                <p class="font-sans font-semibold text-xs tracking-[3px] text-terracotta uppercase">{{ $grupo['titulo'] }}</p>
            </div>
            <div class="space-y-6">
                @foreach ($grupo['galerias'] as $gallery)
                    <section class="rounded-xl border border-graphite/10 bg-white p-6">
                        <div class="flex items-center justify-between mb-5">
                            <h2 class="font-display font-bold text-lg text-graphite">{{ $gallery['nome'] }}</h2>
                            <span class="text-xs text-graphite/50">{{ count($gallery['photos']) }} foto(s)</span>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-5">
                            @forelse ($gallery['photos'] as $index => $photo)
                                <div class="relative group rounded-md overflow-hidden border border-graphite/10">
                                    <div class="relative aspect-square bg-graphite-light/10">
                                        <img src="{{ $photo['url'] }}" alt="" class="w-full h-full object-cover">
                                        <form method="POST" action="{{ route('galleries.destroy', $photo['id']) }}"
                                              onsubmit="return confirm('Excluir esta foto?');"
                                              class="absolute inset-0 flex items-center justify-center bg-graphite/60 opacity-0 group-hover:opacity-100 transition">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs font-medium rounded-md bg-white/90 text-red-600 px-3 py-1.5 hover:bg-white transition">Excluir</button>
                                        </form>

                                        <div class="absolute top-1.5 right-1.5 flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition">
                                            <form method="POST" action="{{ route('galleries.mover', $photo['id']) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="direcao" value="subir">
                                                <button type="submit" @disabled($index === 0)
                                                        class="w-6 h-6 flex items-center justify-center text-xs rounded bg-white/90 text-graphite hover:bg-white transition disabled:opacity-30 disabled:cursor-not-allowed">↑</button>
                                            </form>
                                            <form method="POST" action="{{ route('galleries.mover', $photo['id']) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="direcao" value="descer">
                                                <button type="submit" @disabled($index === count($gallery['photos']) - 1)
                                                        class="w-6 h-6 flex items-center justify-center text-xs rounded bg-white/90 text-graphite hover:bg-white transition disabled:opacity-30 disabled:cursor-not-allowed">↓</button>
                                            </form>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('galleries.legenda', $photo['id']) }}" class="flex items-center gap-1.5 p-1.5 bg-white">
                                        @csrf @method('PATCH')
                                        <input type="text" name="legenda" value="{{ $photo['legenda'] }}" placeholder="Legenda (opcional)"
                                               class="flex-1 min-w-0 text-xs rounded border-graphite/15 focus:border-terracotta focus:ring-terracotta">
                                        <button type="submit" class="text-xs font-medium rounded-md border border-graphite/15 px-2 py-1.5 hover:bg-graphite/5 transition shrink-0">Salvar</button>
                                    </form>
                                </div>
                            @empty
                                <p class="col-span-full text-xs text-graphite/40">Nenhuma foto nessa categoria ainda.</p>
                            @endforelse
                        </div>

                        <form method="POST" action="{{ route('galleries.store') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                            @csrf
                            <input type="hidden" name="gallery_id" value="{{ $gallery['id'] }}">
                            <input type="file" name="foto" accept="image/*" required
                                   class="text-sm text-graphite/70 file:mr-3 file:rounded-md file:border-0 file:bg-graphite/5 file:px-3 file:py-2 file:text-xs file:font-medium hover:file:bg-graphite/10">
                            <input type="text" name="legenda" placeholder="Legenda (opcional)"
                                   class="text-sm rounded-md border-graphite/15 focus:border-terracotta focus:ring-terracotta flex-1 min-w-[160px]">
                            <button type="submit" class="text-xs font-medium rounded-md bg-terracotta text-cream px-4 py-2 hover:bg-terracotta-dark transition">Adicionar foto</button>
                        </form>
                    </section>
                @endforeach
            </div>
        @endif
    @endforeach
</x-app-layout>
