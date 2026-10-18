@if ($paginator->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan <span>{{ $paginator->firstItem() }}</span> – <span>{{ $paginator->lastItem() }}</span> dari <span>{{ $paginator->total() }}</span> artikel
        </div>

        <nav class="pagination-nav" role="navigation" aria-label="Navigasi Halaman">
            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="pagination-btn disabled" aria-disabled="true">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span>Sebelumnya</span>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"></polyline>
                    </svg>
                    <span>Sebelumnya</span>
                </a>
            @endif

            {{-- Angka Halaman --}}
            <div class="pagination-numbers">
                @foreach ($elements as $element)
                    {{-- Separator titik-titik --}}
                    @if (is_string($element))
                        <span class="pagination-ellipsis">{{ $element }}</span>
                    @endif

                    {{-- Link Angka Halaman --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="pagination-number active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="pagination-number">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Tombol Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next">
                    <span>Selanjutnya</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </a>
            @else
                <span class="pagination-btn disabled" aria-disabled="true">
                    <span>Selanjutnya</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </span>
            @endif
        </nav>
    </div>
@endif
