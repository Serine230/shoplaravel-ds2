@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Page <span class="font-semibold text-gray-700">{{ $paginator->currentPage() }}</span>
        </p>

        <div class="flex items-center gap-2">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="flex items-center gap-1.5 px-4 py-2 text-gray-300 cursor-not-allowed rounded-xl border border-gray-100 bg-white text-sm font-medium">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i> Précédent
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="flex items-center gap-1.5 px-4 py-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl border border-gray-100 bg-white transition-colors text-sm font-medium">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i> Précédent
                </a>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="flex items-center gap-1.5 px-4 py-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl border border-gray-100 bg-white transition-colors text-sm font-medium">
                    Suivant <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @else
                <span class="flex items-center gap-1.5 px-4 py-2 text-gray-300 cursor-not-allowed rounded-xl border border-gray-100 bg-white text-sm font-medium">
                    Suivant <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
