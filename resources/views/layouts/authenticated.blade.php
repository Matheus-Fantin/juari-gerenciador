<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth" translate="no">
<head>
    <meta charset="UTF-8">
    <meta name="google" content="notranslate">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — Juari Gerenciador' : 'Juari Gerenciador' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500;1,600&display=swap" rel="stylesheet">

    <style>
        .font-logo { font-family: 'Cormorant Garamond', serif; }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-graphite bg-cream antialiased notranslate min-h-screen flex flex-col">

    <header class="bg-graphite" x-data="{ menuAberto: false }">
        <nav class="max-w-6xl mx-auto flex items-center justify-between px-6 py-4">
            <a href="{{ route('dashboard') }}" class="leading-none font-logo">
                <span class="font-semibold text-2xl tracking-[2px] text-cream">JUARI</span>
                <span class="italic font-medium text-xs text-cream/70 block -mt-1">Painel</span>
            </a>

            <ul class="hidden md:flex items-center gap-6 text-sm text-cream/75">
                <li><a href="{{ route('dashboard') }}" class="hover:text-cream transition {{ request()->routeIs('dashboard') ? 'text-cream font-medium' : '' }}">Painel</a></li>
                <li><a href="{{ route('testimonials.index') }}" class="hover:text-cream transition {{ request()->routeIs('testimonials.*') ? 'text-cream font-medium' : '' }}">Depoimentos</a></li>
                <li><a href="{{ route('galleries.index') }}" class="hover:text-cream transition {{ request()->routeIs('galleries.*') ? 'text-cream font-medium' : '' }}">Galeria</a></li>
                <li><a href="{{ route('site-images.index') }}" class="hover:text-cream transition {{ request()->routeIs('site-images.*') ? 'text-cream font-medium' : '' }}">Imagens do site</a></li>
                <li><a href="{{ route('visits.index') }}" class="hover:text-cream transition {{ request()->routeIs('visits.*') ? 'text-cream font-medium' : '' }}">Visitas</a></li>
                @if (config('services.juari_site.public_url'))
                    <li><a href="{{ config('services.juari_site.public_url') }}" target="_blank" rel="noopener" class="hover:text-cream transition">Ver site ↗</a></li>
                @endif
            </ul>

            <div class="hidden md:flex items-center gap-4">
                <a href="{{ route('profile.edit') }}" class="text-sm text-cream/75 hover:text-cream transition">{{ auth()->user()?->name }}</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm rounded-md border border-cream/25 px-3 py-1.5 text-cream/85 hover:bg-cream/10 transition">Sair</button>
                </form>
            </div>

            <button type="button" @click="menuAberto = !menuAberto" aria-label="Abrir menu"
                    class="md:hidden flex items-center justify-center w-10 h-10 -mr-2 text-cream">
                <svg x-show="!menuAberto" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                    <path d="M3 6h18M3 12h18M3 18h18" />
                </svg>
                <svg x-show="menuAberto" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" style="display: none;">
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </nav>

        <div x-show="menuAberto" x-cloak @click.outside="menuAberto = false" class="md:hidden border-t border-cream/10 px-6 py-4">
            <ul class="flex flex-col gap-1 text-sm text-cream/85">
                <li><a href="{{ route('dashboard') }}" @click="menuAberto = false" class="block py-2.5 {{ request()->routeIs('dashboard') ? 'text-cream font-medium' : '' }}">Painel</a></li>
                <li><a href="{{ route('testimonials.index') }}" @click="menuAberto = false" class="block py-2.5 {{ request()->routeIs('testimonials.*') ? 'text-cream font-medium' : '' }}">Depoimentos</a></li>
                <li><a href="{{ route('galleries.index') }}" @click="menuAberto = false" class="block py-2.5 {{ request()->routeIs('galleries.*') ? 'text-cream font-medium' : '' }}">Galeria</a></li>
                <li><a href="{{ route('site-images.index') }}" @click="menuAberto = false" class="block py-2.5 {{ request()->routeIs('site-images.*') ? 'text-cream font-medium' : '' }}">Imagens do site</a></li>
                <li><a href="{{ route('visits.index') }}" @click="menuAberto = false" class="block py-2.5 {{ request()->routeIs('visits.*') ? 'text-cream font-medium' : '' }}">Visitas</a></li>
                @if (config('services.juari_site.public_url'))
                    <li><a href="{{ config('services.juari_site.public_url') }}" target="_blank" rel="noopener" class="block py-2.5">Ver site ↗</a></li>
                @endif
                <li class="pt-2 mt-2 border-t border-cream/10"><a href="{{ route('profile.edit') }}" @click="menuAberto = false" class="block py-2.5">{{ auth()->user()?->name }} — editar perfil</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="pt-1">
                        @csrf
                        <button type="submit" class="text-sm rounded-md border border-cream/25 px-3 py-2 text-cream/85">Sair</button>
                    </form>
                </li>
            </ul>
        </div>
    </header>

    @isset($header)
        <div class="bg-white border-b border-graphite/10">
            <div class="max-w-6xl mx-auto px-6 py-6">
                {{ $header }}
            </div>
        </div>
    @endisset

    <main class="flex-1">
        <div class="max-w-6xl mx-auto px-6 py-10">
            @if (session('status'))
                <div class="mb-6 rounded-md bg-terracotta/10 border border-terracotta/20 px-4 py-3 text-sm text-terracotta-dark">
                    {{ session('status') }}
                </div>
            @endif

            {{ $slot }}
        </div>
    </main>

    <footer class="border-t border-graphite/10">
        <div class="max-w-6xl mx-auto px-6 py-5 text-xs text-graphite/50">
            &copy; {{ date('Y') }} Juari Eventos — Gerenciador interno
        </div>
    </footer>

</body>
</html>
