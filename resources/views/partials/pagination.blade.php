@if ($paginator->hasPages())
    <div class="pagination-wrap">
        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="page-btn page-disabled">&#8249; Prev</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="page-btn">&#8249; Prev</a>
        @endif

        {{-- Page numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="page-btn page-disabled">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-btn page-active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="page-btn">Next &#8250;</a>
        @else
            <span class="page-btn page-disabled">Next &#8250;</span>
        @endif

        <span class="page-info">
            {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} dari {{ $paginator->total() }} data
        </span>
    </div>
@endif
