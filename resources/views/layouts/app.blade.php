{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>VitaSolutions — @yield('title', 'Dashboard')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['DM Sans', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#f0fdf6', 100: '#dcfce9', 200: '#bbf7d2',
                            400: '#4ade80', 500: '#22c55e', 600: '#16a34a',
                            700: '#15803d', 800: '#166534', 900: '#14532d',
                        }
                    }
                }
            }
        }
    </script>

    @livewireStyles

    <style>
        body { font-family: 'DM Sans', sans-serif; }
        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px; border-radius: 8px;
            font-size: 14px; font-weight: 500;
            color: #94a3b8; transition: all 0.15s;
            text-decoration: none;
        }
        .sidebar-link:hover { background: #1e293b; color: #fff; }
        .sidebar-link.active { background: #0F6E56; color: #fff; }
        .sidebar-section {
            font-size: 11px; font-weight: 600; color: #475569;
            text-transform: uppercase; letter-spacing: 0.1em;
            padding: 0 16px; margin: 24px 0 8px;
        }
        #sidebar { transition: transform 0.25s ease; }
        #sidebar-overlay { transition: opacity 0.25s ease; }

        .flash-banner { animation: flashFade 4s ease forwards; }
        @keyframes flashFade {
            0%   { opacity: 1; transform: translateY(0); }
            80%  { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-6px); pointer-events: none; }
        }

        /* Inputs tema oscuro */
        input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
        select,
        textarea {
            background-color: #0f172a !important;
            color: #e2e8f0 !important;
            border-color: #1e293b !important;
        }

        input::placeholder,
        textarea::placeholder {
            color: #475569 !important;
        }

        input:focus:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
        select:focus,
        textarea:focus {
            border-color: #0F6E56 !important;
            outline: none !important;
            box-shadow: 0 0 0 2px rgba(15, 110, 86, 0.25) !important;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 min-h-screen flex">

    {{-- OVERLAY móvil --}}
    <div id="sidebar-overlay"
         class="fixed inset-0 bg-black/60 z-20 hidden opacity-0 lg:hidden"
         onclick="closeSidebar()"></div>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
           class="w-64 bg-slate-900 border-r border-slate-800 flex flex-col fixed top-0 left-0 z-30 -translate-x-full lg:translate-x-0"
           style="height: 100dvh">

        {{-- Logo --}}
        <div class="px-6 py-5 border-b border-slate-800 flex-shrink-0">
            <div class="flex items-center gap-3">
                @php $center = auth()->user()->center; @endphp
                @if($center && $center->logo_url)
                <img src="{{ $center->logo_url }}" class="w-8 h-8 rounded-lg object-cover">
                @else
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background:#0F6E56">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                @endif
                <div class="flex-1 min-w-0">
                    <span class="font-semibold text-white text-base truncate block">
                        {{ $center ? $center->name : 'VitaSolutions' }}
                    </span>
                    @if(auth()->user()->isSuperAdmin())
                    <span class="text-xs text-purple-400">Super Admin</span>
                    @elseif(auth()->user()->isOwner())
                    <span class="text-xs text-teal-400">Admin Owner</span>
                    @endif
                </div>
                <button onclick="closeSidebar()" class="ml-auto lg:hidden text-slate-400 hover:text-white flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Navegación --}}
        <nav class="flex-1 px-3 py-4 overflow-y-auto min-h-0">

            @if(auth()->user()->isSuperAdmin())

                {{-- SUPERADMIN --}}
                <p class="sidebar-section">Super Admin</p>
                <a href="{{ route('superadmin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard global
                </a>

                <p class="sidebar-section">Centros</p>
                <a href="{{ route('superadmin.centers.index') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.centers.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Todos los centros
                </a>
                <a href="{{ route('superadmin.centers.create') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin.centers.create') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nuevo centro
                </a>

            @elseif(auth()->user()->isStrictAdmin())

                {{-- ADMIN --}}
                <p class="sidebar-section">General</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <p class="sidebar-section">Clientes</p>
                <a href="{{ route('admin.clientes.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.clientes.index') || (request()->routeIs('admin.clientes.*') && !request()->routeIs('admin.clientes.create')) ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Todos los clientes
                </a>
                <a href="{{ route('admin.clientes.create') }}"
                   class="sidebar-link {{ request()->routeIs('admin.clientes.create') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    Nuevo cliente
                </a>

                <p class="sidebar-section">Catálogo</p>
                <a href="{{ route('admin.productos.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.productos.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Productos / Suplementos
                </a>

                <p class="sidebar-section">Planes</p>
                <a href="{{ route('admin.planes.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.planes.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Planes / Suscripciones
                </a>
                <a href="{{ route('admin.client-planes.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.client-planes.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Suscripciones activas
                </a>

                <p class="sidebar-section">Mi Centro</p>
                <a href="{{ route('admin.centro.show') }}"
                   class="sidebar-link {{ request()->routeIs('admin.centro.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Mi centro
                </a>

            @else

                {{-- CLIENTE --}}
                <p class="sidebar-section">Mi salud</p>
                <a href="{{ route('cliente.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Mi dashboard
                </a>
                <a href="{{ route('cliente.perfil') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.perfil') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Mi perfil de salud
                </a>

                <p class="sidebar-section">Registro</p>
                <a href="{{ route('cliente.mediciones.index') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.mediciones.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Mis mediciones
                </a>
                <a href="{{ route('cliente.cuestionarios.index') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.cuestionarios.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                    Cuestionarios
                </a>
                <a href="{{ route('cliente.protocolos.index') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.protocolos.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                    </svg>
                    Mis suplementos
                </a>
                <a href="{{ route('cliente.archivos.index') }}"
                   class="sidebar-link {{ request()->routeIs('cliente.archivos.*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Mis archivos
                </a>

            @endif
        </nav>

        {{-- Usuario --}}
        <div class="px-4 py-4 border-t border-slate-800 flex-shrink-0">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                     style="background:#0F6E56">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500">
                        @if(auth()->user()->isSuperAdmin())
                            Super Administrador
                        @elseif(auth()->user()->isOwner())
                            Admin Owner
                        @elseif(auth()->user()->isStrictAdmin())
                            Administrador
                        @else
                            Cliente
                        @endif
                    </p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left hover:bg-red-900/30 hover:text-red-400">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Cerrar sesión
                </button>
            </form>
        </div>
    </aside>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">

        <header class="bg-slate-900/80 backdrop-blur border-b border-slate-800 px-4 lg:px-8 py-4 flex items-center justify-between sticky top-0 z-20">
            <div class="flex items-center gap-3">
                <button onclick="openSidebar()" class="lg:hidden text-slate-400 hover:text-white p-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-base lg:text-lg font-semibold text-white">@yield('title', 'Dashboard')</h1>
                    @hasSection('subtitle')
                    <p class="text-xs lg:text-sm text-slate-400">@yield('subtitle')</p>
                    @endif
                </div>
            </div>
            <span class="text-xs lg:text-sm text-slate-500 hidden sm:block">{{ now()->isoFormat('D MMM YYYY') }}</span>
        </header>

        @if(session('success'))
        <div class="flash-banner mx-4 lg:mx-8 mt-4 flex items-center gap-3 bg-teal-600/15 border border-teal-500/30 text-teal-300 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flash-banner mx-4 lg:mx-8 mt-4 flex items-center gap-3 bg-red-600/15 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="flash-banner mx-4 lg:mx-8 mt-4 flex items-center gap-3 bg-red-600/15 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $errors->first() }}
        </div>
        @endif

        <main class="flex-1 px-4 lg:px-8 py-6">
            @yield('content')
        </main>

        <footer class="px-4 lg:px-8 py-4 border-t border-slate-800 text-xs text-slate-600">
            VitaSolutions © {{ date('Y') }} — Sistema privado de gestión nutraceutica
        </footer>
    </div>

    @livewireScripts
    @stack('scripts')

    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden', 'opacity-0');
            overlay.classList.add('opacity-100');
            document.body.style.overflow = 'hidden';
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('opacity-0');
            setTimeout(() => {
                overlay.classList.add('hidden');
                overlay.classList.remove('opacity-100');
            }, 250);
            document.body.style.overflow = '';
        }

        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) closeSidebar();
            });
        });
    </script>
</body>
</html>