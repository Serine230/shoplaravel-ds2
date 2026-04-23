@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        <p class="text-sm text-gray-500">
            Affichage de
            <span class="font-semibold text-gray-700">{{ $paginator->firstItem() }}</span>
            à
            <span class="font-semibold text-gray-700">{{ $paginator->lastItem() }}</span>
            sur
            <span class="font-semibold text-gray-700">{{ $paginator->total() }}</span>
            résultats
        </p>

        <div class="flex items-center gap-1">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-gray-300 cursor-not-allowed rounded-xl border border-gray-100 bg-white">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl border border-gray-100 bg-white transition-colors">
                    <i data-lucide="chevron-left" class="w-4 h-4"></i>
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 text-gray-400">{{ $element }}</span>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3.5 py-2 bg-indigo-600 text-white font-bold rounded-xl text-sm">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3.5 py-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl border border-gray-100 bg-white transition-colors text-sm font-medium">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-2 text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-xl border border-gray-100 bg-white transition-colors">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @else
                <span class="px-3 py-2 text-gray-300 cursor-not-allowed rounded-xl border border-gray-100 bg-white">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </span>
            @endif
        </div>
    </nav>
@endif
