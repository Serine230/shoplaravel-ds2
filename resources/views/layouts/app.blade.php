<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ShopLaravel') — Boutique Premium</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#4F46E5', dark: '#3730A3', light: '#818CF8' },
                        accent:  { DEFAULT: '#7C3AED', light: '#A78BFA' },
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .product-card:hover { transform: translateY(-4px); }
        .product-card { transition: transform .25s ease, box-shadow .25s ease; }
        .btn-primary { @apply bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 inline-flex items-center gap-2; }
        .btn-outline { @apply border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white font-semibold px-5 py-2.5 rounded-xl transition-all duration-200 inline-flex items-center gap-2; }
        .input-field { @apply w-full border border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 bg-white; }
        .card { @apply bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden; }
        .badge-yellow { @apply bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-1 rounded-full; }
        .badge-green  { @apply bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-1 rounded-full; }
        .badge-blue   { @apply bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1 rounded-full; }
        .badge-red    { @apply bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-1 rounded-full; }
        .badge-purple { @apply bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-1 rounded-full; }
    </style>

    @stack('styles')
</head>
<body class="font-sans bg-gray-50 text-gray-900 antialiased">

{{-- ═══════ NAVBAR ═══════ --}}
<nav x-data="{ open: false, search: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-extrabold text-xl text-indigo-600">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <span class="text-white text-sm font-bold">SL</span>
                </div>
                ShopLaravel
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-6 text-sm font-medium text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Accueil</a>
                <a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition-colors">Catalogue</a>
                @auth
                    <a href="{{ route('products.create') }}" class="hover:text-indigo-600 transition-colors">Vendre</a>
                @endauth
            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-3">

                {{-- Search toggle --}}
                <button @click="search = !search" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-xl transition-colors">
                    <i data-lucide="search" class="w-5 h-5"></i>
                </button>

                @auth
                    {{-- Wishlist --}}
                    <a href="{{ route('wishlist.index') }}" class="p-2 text-gray-500 hover:text-red-500 hover:bg-gray-100 rounded-xl transition-colors">
                        <i data-lucide="heart" class="w-5 h-5"></i>
                    </a>

                    {{-- Cart --}}
                    <a href="{{ route('cart.index') }}" class="relative p-2 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-xl transition-colors">
                        <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                        @php $cartCount = array_sum(session('cart', [])); @endphp
                        @if($cartCount > 0)
                            <span id="cart-badge" class="absolute -top-1 -right-1 bg-indigo-600 text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                    {{-- Messages --}}
                    <a href="{{ route('messages.index') }}" class="p-2 text-gray-500 hover:text-indigo-600 hover:bg-gray-100 rounded-xl transition-colors">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                    </a>

                    {{-- User Dropdown --}}
                    <div x-data="{ menu: false }" class="relative">
                        <button @click="menu = !menu" class="flex items-center gap-2 pl-1 pr-2 py-1 hover:bg-gray-100 rounded-xl transition-colors">
                            <img src="{{ auth()->user()->avatar_url }}" alt="avatar"
                                 class="w-8 h-8 rounded-full object-cover border-2 border-indigo-200">
                            <span class="hidden md:block text-sm font-medium text-gray-700">{{ Str::limit(auth()->user()->name, 12) }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-gray-400"></i>
                        </button>

                        <div x-show="menu" @click.outside="menu = false" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-lg border border-gray-100 py-2 z-50">

                            <div class="px-4 py-2 border-b border-gray-100">
                                <p class="font-semibold text-sm text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                @if(auth()->user()->isAdmin())
                                    <span class="badge-purple text-xs mt-1 inline-block">Admin</span>
                                @endif
                            </div>

                            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i data-lucide="user" class="w-4 h-4"></i> Mon profil
                            </a>
                            <a href="{{ route('products.mine') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i data-lucide="package" class="w-4 h-4"></i> Mes produits
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i> Mes commandes
                            </a>

                            @if(auth()->user()->isAdmin())
                                <div class="border-t border-gray-100 mt-1 pt-1">
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-purple-700 hover:bg-purple-50 transition-colors font-semibold">
                                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Admin Panel
                                    </a>
                                </div>
                            @endif

                            <div class="border-t border-gray-100 mt-1 pt-1">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i data-lucide="log-out" class="w-4 h-4"></i> Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-600 hover:text-indigo-600 transition-colors hidden md:block">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm !px-4 !py-2">Inscription</a>
                @endauth

                {{-- Mobile menu button --}}
                <button @click="open = !open" class="md:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-xl">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Search Bar --}}
    <div x-show="search" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="border-t border-gray-100 px-4 py-3 bg-white">
        <form action="{{ route('products.index') }}" method="GET" class="max-w-2xl mx-auto">
            <div class="relative">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher un produit..."
                       class="input-field pl-10 !rounded-full" autofocus>
                <i data-lucide="search" class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"></i>
                <button type="submit" class="absolute right-2 top-1.5 bg-indigo-600 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-indigo-700 transition-colors">
                    Chercher
                </button>
            </div>
        </form>
    </div>
</nav>

{{-- ═══════ FLASH MESSAGES ═══════ --}}
@if(session('success'))
    <div id="flash-success" class="fixed top-20 right-4 z-50 bg-white border-l-4 border-green-500 rounded-xl shadow-lg p-4 flex items-center gap-3 max-w-sm animate-bounce-in">
        <div class="bg-green-100 rounded-full p-2"><i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i></div>
        <p class="text-sm font-medium text-gray-700">{{ session('success') }}</p>
        <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">✕</button>
    </div>
    <script>setTimeout(() => { const el = document.getElementById('flash-success'); if(el) el.remove(); }, 5000);</script>
@endif

@if(session('error') || $errors->any())
    <div class="fixed top-20 right-4 z-50 bg-white border-l-4 border-red-500 rounded-xl shadow-lg p-4 flex items-center gap-3 max-w-sm">
        <div class="bg-red-100 rounded-full p-2"><i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i></div>
        <p class="text-sm font-medium text-gray-700">{{ session('error') ?? $errors->first() }}</p>
        <button onclick="this.parentElement.remove()" class="ml-auto text-gray-400 hover:text-gray-600">✕</button>
    </div>
@endif

{{-- ═══════ CONTENT ═══════ --}}
<main>
    @yield('content')
</main>

{{-- ═══════ FOOTER ═══════ --}}
<footer class="bg-gray-900 text-gray-300 mt-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 font-extrabold text-xl text-white mb-4">
                    <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm font-bold">SL</span>
                    </div>
                    ShopLaravel
                </div>
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                    Votre marketplace de confiance. Des milliers de produits, des vendeurs certifiés, une expérience d'achat premium.
                </p>
                <div class="flex gap-4 mt-6">
                    <a href="#" class="bg-gray-800 hover:bg-indigo-600 p-2 rounded-xl transition-colors"><i data-lucide="facebook" class="w-4 h-4"></i></a>
                    <a href="#" class="bg-gray-800 hover:bg-indigo-600 p-2 rounded-xl transition-colors"><i data-lucide="instagram" class="w-4 h-4"></i></a>
                    <a href="#" class="bg-gray-800 hover:bg-indigo-600 p-2 rounded-xl transition-colors"><i data-lucide="twitter" class="w-4 h-4"></i></a>
                </div>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Navigation</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}" class="hover:text-indigo-400 transition-colors">Accueil</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-indigo-400 transition-colors">Catalogue</a></li>
                    @auth
                        <li><a href="{{ route('orders.index') }}" class="hover:text-indigo-400 transition-colors">Mes commandes</a></li>
                        <li><a href="{{ route('wishlist.index') }}" class="hover:text-indigo-400 transition-colors">Wishlist</a></li>
                    @endauth
                </ul>
            </div>

            <div>
                <h3 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Support</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="#" class="hover:text-indigo-400 transition-colors">FAQ</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition-colors">Contact</a></li>
                    <li><a href="#" class="hover:text-indigo-400 transition-colors">Politique de retour</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 mt-12 pt-6 flex flex-col md:flex-row items-center justify-between text-sm text-gray-500">
            <p>© {{ date('Y') }} ShopLaravel. Développé avec Laravel & Tailwind CSS.</p>
            <p class="mt-2 md:mt-0">DS2 — Programmation Web 2 — ESSEC 2025–2026</p>
        </div>
    </div>
</footer>

<script>
    // ── Init Lucide icons ──────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => lucide.createIcons());
    document.addEventListener('alpine:initialized', () => lucide.createIcons());

    // ── Global: Toggle Wishlist ────────────────────────────────
    // Called from product-card component everywhere on the site
    function toggleWishlist(productId, btn) {
        fetch(`/wishlist/toggle/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(r => r.json())
        .then(data => {
            // Re-init icons after DOM change
            const icon = btn.querySelector('i[data-lucide="heart"]');
            if (!icon) return;
            if (data.inWishlist) {
                icon.classList.add('text-red-500', 'fill-red-500');
                icon.classList.remove('text-gray-400');
            } else {
                icon.classList.remove('text-red-500', 'fill-red-500');
                icon.classList.add('text-gray-400');
            }
        })
        .catch(() => {
            // Not logged in — redirect to login
            window.location.href = '/connexion';
        });
    }

    // ── Global: Update cart badge after AJAX add-to-cart ──────
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[action*="cart/ajouter"]').forEach(form => {
            form.addEventListener('submit', function (e) {
                // Allow normal submit — badge will update on page reload
                // For AJAX carts, intercept here if needed
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>
