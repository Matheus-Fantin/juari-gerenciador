<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-extrabold text-2xl text-graphite">Visitas do site</h1>
        <p class="text-sm text-graphite/60 mt-1">Quantas pessoas visitaram cada página do site Juari Eventos.</p>
    </x-slot>

    @unless ($conectado)
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">
            Não foi possível conectar ao site (juari-eventos-02) para buscar os números de visitas.
        </div>
    @else
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <div class="rounded-xl border border-graphite/10 bg-white p-6">
                <p class="font-sans font-semibold text-xs tracking-[3px] text-terracotta uppercase mb-2">Hoje</p>
                <p class="font-display font-bold text-3xl text-graphite">{{ $dados['hoje'] }}</p>
            </div>
            <div class="rounded-xl border border-graphite/10 bg-white p-6">
                <p class="font-sans font-semibold text-xs tracking-[3px] text-terracotta uppercase mb-2">Esta semana</p>
                <p class="font-display font-bold text-3xl text-graphite">{{ $dados['semana'] }}</p>
            </div>
            <div class="rounded-xl border border-graphite/10 bg-white p-6">
                <p class="font-sans font-semibold text-xs tracking-[3px] text-terracotta uppercase mb-2">Este mês</p>
                <p class="font-display font-bold text-3xl text-graphite">{{ $dados['mes'] }}</p>
            </div>
            <div class="rounded-xl border border-graphite/10 bg-white p-6">
                <p class="font-sans font-semibold text-xs tracking-[3px] text-terracotta uppercase mb-2">Desde o início</p>
                <p class="font-display font-bold text-3xl text-graphite">{{ $dados['total'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2 mb-8">
            <div class="rounded-xl border border-graphite/10 bg-white p-6">
                <h2 class="font-display font-bold text-lg text-graphite mb-4">Visitas por página</h2>
                <div class="space-y-3">
                    @php $maxPorPagina = max(1, collect($dados['por_pagina'])->max('total')); @endphp
                    @foreach ($dados['por_pagina'] as $p)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-graphite/80">{{ $p['label'] }}</span>
                                <span class="font-medium text-graphite">{{ $p['total'] }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-graphite/10 overflow-hidden">
                                <div class="h-full bg-terracotta rounded-full" style="width: {{ max(4, round($p['total'] / $maxPorPagina * 100)) }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-xl border border-graphite/10 bg-white p-6">
                <h2 class="font-display font-bold text-lg text-graphite mb-4">Últimos 30 dias</h2>
                @if (count($dados['ultimos_30_dias']) === 0)
                    <p class="text-sm text-graphite/50">Ainda não há visitas registradas.</p>
                @else
                    @php $maxDia = max(1, collect($dados['ultimos_30_dias'])->max('total')); @endphp
                    <div class="flex items-end gap-1 h-32">
                        @foreach ($dados['ultimos_30_dias'] as $dia)
                            <div class="flex-1 bg-terracotta/70 rounded-t hover:bg-terracotta transition"
                                 style="height: {{ max(6, round($dia['total'] / $maxDia * 100)) }}%"
                                 title="{{ \Illuminate\Support\Carbon::parse($dia['dia'])->format('d/m') }}: {{ $dia['total'] }} visita(s)">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endunless

    <div class="rounded-xl border border-graphite/10 bg-white p-6">
        <h2 class="font-display font-bold text-lg text-graphite mb-2">Mapa de calor de cliques</h2>
        <p class="text-sm text-graphite/70 leading-relaxed mb-4">
            Para ver exatamente onde as pessoas mais clicam no site (mapa de calor) e assistir gravações de como
            elas navegam, usamos o <strong>Microsoft Clarity</strong> — uma ferramenta gratuita da Microsoft feita
            para isso.
        </p>
        <ol class="list-decimal list-inside text-sm text-graphite/70 space-y-1.5 mb-4">
            <li>Acesse <a href="https://clarity.microsoft.com" target="_blank" rel="noopener" class="text-terracotta hover:underline">clarity.microsoft.com</a> e crie uma conta gratuita (pode ser com o mesmo e-mail do painel).</li>
            <li>Cadastre o site <code class="bg-graphite/5 px-1.5 py-0.5 rounded">juari-eventos-02</code> e copie o "Project ID" gerado.</li>
            <li>Cole esse código na variável <code class="bg-graphite/5 px-1.5 py-0.5 rounded">CLARITY_PROJECT_ID</code> nas configurações do site na hospedagem (Laravel Cloud) e reimplante.</li>
            <li>Volte em clarity.microsoft.com sempre que quiser ver o mapa de calor e as gravações de sessão.</li>
        </ol>
        <p class="text-xs text-graphite/50">
            Assim que essa chave for configurada, o site passa a mostrar um aviso de cookies/analytics no rodapé
            automaticamente, para deixar isso transparente para quem visita.
        </p>
    </div>
</x-app-layout>
