@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-500 leading-5">
                Menampilkan
                <span class="font-semibold text-gray-800">{{ $paginator->firstItem() }}</span>
                –
                <span class="font-semibold text-gray-800">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-semibold text-gray-800">{{ $paginator->total() }}</span>
                kos
            </p>
        </div>

        <div>
            <span class="relative z-0 inline-flex rounded-xl shadow-sm gap-1">

                {{-- Previous --}}
                @if ($paginator->onFirstPage())
                <span class="px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 rounded-xl cursor-not-allowed">‹</span>
                @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-brand-300 hover:text-brand-600 transition">
                    ‹
                </a>
                @endif

                {{-- Pages --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                    <span class="px-3 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-200 rounded-xl">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                            <span class="px-3 py-2 text-sm font-semibold text-white bakos-gradient border border-brand-500 rounded-xl">{{ $page }}</span>
                            @else
                            <a href="{{ $url }}"
                               class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-brand-300 hover:text-brand-600 transition">
                                {{ $page }}
                            </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-brand-300 hover:text-brand-600 transition">
                    ›
                </a>
                @else
                <span class="px-3 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 rounded-xl cursor-not-allowed">›</span>
                @endif

            </span>
        </div>
    </div>

    {{-- Mobile --}}
    <div class="flex justify-between flex-1 sm:hidden gap-3">
        @if ($paginator->onFirstPage())
        <span class="px-4 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 rounded-xl cursor-not-allowed">← Sebelumnya</span>
        @else
        <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-blue-50 transition">← Sebelumnya</a>
        @endif

        @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-blue-50 transition">Berikutnya →</a>
        @else
        <span class="px-4 py-2 text-sm font-medium text-gray-300 bg-white border border-gray-200 rounded-xl cursor-not-allowed">Berikutnya →</span>
        @endif
    </div>
</nav>
@endif
