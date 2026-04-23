<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — @yield('title', 'Tableau de bord') | ShopLaravel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { theme: { extend: { colors: { primary: '#4F46E5' } } } }</script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .nav-link { @apply flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 hover:bg-white/10 hover:text-white transition-all duration-200; }
        .nav-link.active { @apply bg-indigo-600 text-white shadow-md; }
        .stat-card { @apply bg-white rounded-2xl p-6 shadow-sm border border-gray-100; }
        .admin-table th { @apply px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider bg-gray-50; }
        .admin-table td { @apply px-4 py-3 text-sm text-gray-700 border-t border-gray-100; }
        .admin-table tr:hover td { @apply bg-indigo-50/50; }
        .input-field { @apply w-full border border-gray-200 rounded-xl px-4 py-2.5 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm; }
        .btn-primary { @apply bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 inline-flex items-center gap-2 text-sm; }
        .btn-danger  { @apply bg-red-50 hover:bg-red-600 text-red-600 hover:text-white font-semibold px-4 py-2.5 rounded-xl transition-all duration-200 inline-flex items-center gap-2 text-sm; }
    </style>
</head>
<body class="font-[Inter] bg-gray-50 antialiased" x-data="{ sidebarOpen: true }">

<div class="flex h-screen overflow-hidden">
    {{-- ═══════ SIDEBAR ═══════ --}}
    <aside :class="sidebarOpen ? 'w-64' : 'w-16'"
           class="bg-gray-900 flex flex-col transition-all duration-300 overflow-hidden flex-shrink-0">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-4 h-16 border-b border-white/10">
            <div class="w-8 h-8 bg-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                <span class="text-white font-bold text-sm">SL</span>
            </div>
            <span x-show="sidebarOpen" class="text-white font-bold text-lg">Admin</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Tableau de bord</span>
            </a>
            <a href="{{ route('admin.products.index') }}"
               class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i data-lucide="package" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Produits</span>
            </a>
            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i data-lucide="tag" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Catégories</span>
            </a>
            <a href="{{ route('admin.orders.index') }}"
               class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i data-lucide="shopping-bag" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Commandes</span>
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
                <span x-show="sidebarOpen">Utilisateurs</span>
            </a>

            <div class="pt-4 border-t border-white/10 mt-4">
                <a href="{{ route('home') }}" class="nav-link">
                    <i data-lucide="store" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="sidebarOpen">Voir la boutique</span>
                </a>
            </div>
        </nav>

        {{-- User --}}
        <div class="p-3 border-t border-white/10">
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                <div x-show="sidebarOpen" class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-gray-400 text-xs">Administrateur</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- ═══════ MAIN ═══════ --}}
    <div class="flex-1 flex flex-col overflow-hidden">
        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-100 h-16 flex items-center justify-between px-6 flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                    <i data-lucide="panel-left" class="w-5 h-5"></i>
                </button>
                <div>
                    <h1 class="font-bold text-gray-900 text-lg">@yield('title', 'Tableau de bord')</h1>
                    <p class="text-xs text-gray-500">@yield('subtitle', 'Bienvenue dans votre espace admin')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(session('success'))
                    <div class="bg-green-100 text-green-700 text-sm px-3 py-1.5 rounded-xl flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 text-sm text-gray-500 hover:text-red-600 px-3 py-2 rounded-xl hover:bg-red-50 transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        Déconnexion
                    </button>
                </form>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>

<script>document.addEventListener('DOMContentLoaded', () => lucide.createIcons());</script>
@stack('scripts')
</body>
</html>
