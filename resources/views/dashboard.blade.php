<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-extrabold text-2xl text-graphite">Olá, {{ auth()->user()->name }}</h1>
        <p class="text-sm text-graphite/60 mt-1">Gerenciador do site Juari Eventos.</p>
    </x-slot>

    @unless ($conectado)
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">
            Não foi possível conectar ao site (juari-eventos-02). Confira se ele está no ar e se o token da API
            está configurado corretamente no <code>.env</code> deste gerenciador.
        </div>
    @endunless

    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <a href="{{ route('testimonials.index') }}"
           class="rounded-xl border border-graphite/10 bg-white p-6 hover:border-terracotta hover:shadow-sm transition">
            <p class="font-sans font-semibold text-xs tracking-[3px] text-terracotta uppercase mb-2">Depoimentos</p>
            <h2 class="font-display font-bold text-lg text-graphite mb-1">Aprovar ou remover depoimentos</h2>
            <p class="text-sm text-graphite/60">{{ $pendentes }} pendente(s) de revisão.</p>
        </a>

        <a href="{{ route('galleries.index') }}"
           class="rounded-xl border border-graphite/10 bg-white p-6 hover:border-terracotta hover:shadow-sm transition">
            <p class="font-sans font-semibold text-xs tracking-[3px] text-terracotta uppercase mb-2">Galeria</p>
            <h2 class="font-display font-bold text-lg text-graphite mb-1">Adicionar, legendar e ordenar fotos</h2>
            <p class="text-sm text-graphite/60">{{ $totalFotos }} foto(s) publicadas no site.</p>
        </a>

        <a href="{{ route('site-images.index') }}"
           class="rounded-xl border border-graphite/10 bg-white p-6 hover:border-terracotta hover:shadow-sm transition">
            <p class="font-sans font-semibold text-xs tracking-[3px] text-terracotta uppercase mb-2">Imagens do site</p>
            <h2 class="font-display font-bold text-lg text-graphite mb-1">Trocar as capas do site</h2>
            <p class="text-sm text-graphite/60">Capa da home, da galeria e as fotos da página Sobre.</p>
        </a>
    </div>
</x-app-layout>
