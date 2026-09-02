<x-app-layout>
    <x-slot name="header">
        <h1 class="font-display font-extrabold text-2xl text-graphite">Depoimentos</h1>
        <p class="text-sm text-graphite/60 mt-1">Aprove os depoimentos enviados pelo site ou remova os que não devem aparecer.</p>
    </x-slot>

    @if ($erro)
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">{{ $erro }}</div>
    @endif

    @if (session('erro'))
        <div class="mb-6 rounded-md bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-600">{{ session('erro') }}</div>
    @endif

    <div class="space-y-4">
        @forelse ($depoimentos as $d)
            <div class="rounded-xl border border-graphite/10 bg-white p-6 flex flex-col sm:flex-row sm:items-start gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $d['publicado'] ? 'bg-terracotta/10 text-terracotta' : 'bg-graphite/10 text-graphite/60' }}">
                            {{ $d['publicado'] ? 'Publicado' : 'Pendente' }}
                        </span>
                        <div class="flex text-terracotta">
                            @for ($s = 1; $s <= 5; $s++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 {{ $s > $d['nota'] ? 'text-graphite/15' : '' }}" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2Z"></path>
                                </svg>
                            @endfor
                        </div>
                    </div>
                    <p class="text-sm text-graphite/80 mb-2">&ldquo;{{ $d['texto'] }}&rdquo;</p>
                    <p class="text-xs text-graphite/50">{{ $d['autor'] }} — {{ $d['evento_tipo'] }} · {{ \Illuminate\Support\Carbon::parse($d['created_at'])->format('d/m/Y') }}</p>
                </div>

                <div class="flex sm:flex-col gap-2 shrink-0">
                    @if ($d['publicado'])
                        <form method="POST" action="{{ route('testimonials.unpublish', $d['id']) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full text-xs font-medium rounded-md border border-graphite/15 px-3 py-2 hover:bg-graphite/5 transition">Despublicar</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('testimonials.approve', $d['id']) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="w-full text-xs font-medium rounded-md bg-terracotta text-cream px-3 py-2 hover:bg-terracotta-dark transition">Aprovar</button>
                        </form>
                    @endif
                    <form method="POST" action="{{ route('testimonials.destroy', $d['id']) }}"
                          onsubmit="return confirm('Excluir este depoimento definitivamente?');">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full text-xs font-medium rounded-md border border-red-200 text-red-600 px-3 py-2 hover:bg-red-50 transition">Excluir</button>
                    </form>
                </div>
            </div>
        @empty
            @unless ($erro)
                <p class="text-sm text-graphite/50">Nenhum depoimento enviado ainda.</p>
            @endunless
        @endforelse
    </div>
</x-app-layout>
