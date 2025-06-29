@if ($paginator->hasPages())
    <div class="pagination">
        <div class="flex items-center justify-between">
            {{-- Mobile Pagination --}}
            <div class="flex justify-between flex-1 sm:hidden">
                @if ($paginator->onFirstPage())
                    <span class="pagination-disabled">
                        <i class="fas fa-chevron-left mr-1"></i> Previous
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link">
                        <i class="fas fa-chevron-left mr-1"></i> Previous
                    </a>
                @endif

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link">
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </a>
                @else
                    <span class="pagination-disabled">
                        Next <i class="fas fa-chevron-right ml-1"></i>
                    </span>
                @endif
            </div>

            {{-- Desktop Pagination --}}
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div class="pagination-info">
                    <span>Showing</span>
                    <span class="font-semibold">{{ $paginator->firstItem() }}</span>
                    <span>to</span>
                    <span class="font-semibold">{{ $paginator->lastItem() }}</span>
                    <span>of</span>
                    <span class="font-semibold">{{ $paginator->total() }}</span>
                    <span>results</span>
                </div>

                <div class="pagination-links">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span class="pagination-disabled">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link" rel="prev">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span class="pagination-ellipsis">{{ $element }}</span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="pagination-current">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}" class="pagination-link">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link" rel="next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    @else
                        <span class="pagination-disabled">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            margin-top: 20px;
        }

        .pagination-info {
            color: var(--text-color);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-links {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .pagination-link,
        .pagination span {
            padding: 8px 16px;
            border-radius: var(--border-radius);
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            color: var(--text-color);
            text-decoration: none;
            font-size: 0.9rem;
            transition: transform 0.2s, box-shadow 0.3s, background 0.3s;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
        }

        .pagination-link:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(59, 130, 246, 0.3);
        }

        .pagination-current {
            background: var(--primary-color);
            color: white;
            font-weight: 600;
        }

        .pagination-disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .pagination-ellipsis {
            padding: 8px 12px;
            color: var(--text-color);
            opacity: 0.7;
        }

        @media (max-width: 640px) {
            .pagination-info {
                display: none;
            }
            
            .pagination-links {
                gap: 2px;
            }
            
            .pagination-link,
            .pagination span {
                padding: 6px 12px;
                min-width: 36px;
                height: 36px;
                font-size: 0.8rem;
            }
        }
    </style>
@endif 