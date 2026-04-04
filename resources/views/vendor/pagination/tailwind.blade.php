@if ($paginator->hasPages())
    @php
        $isRtl = app()->getLocale() === 'ar';
        $prevIcon = $isRtl ? 'fa-chevron-right' : 'fa-chevron-left';
        $nextIcon = $isRtl ? 'fa-chevron-left' : 'fa-chevron-right';
    @endphp
    <nav class="e-pagination" role="navigation" aria-label="Pagination">
        <div class="e-pagination-info">
            <span>{{ __('messages.showing') ?? 'عرض' }} {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} {{ __('messages.of') ?? 'من' }} {{ $paginator->total() }}</span>
        </div>

        <ul class="e-pagination-list">
            {{-- Previous --}}
            <li class="e-page-item {{ $paginator->onFirstPage() ? 'disabled' : '' }}">
                @if ($paginator->onFirstPage())
                    <span class="e-page-link"><i class="fas {{ $prevIcon }}"></i></span>
                @else
                    <a class="e-page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas {{ $prevIcon }}"></i></a>
                @endif
            </li>

            {{-- Pages --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="e-page-item disabled"><span class="e-page-link e-page-dots">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="e-page-item {{ $page == $paginator->currentPage() ? 'active' : '' }}">
                            @if ($page == $paginator->currentPage())
                                <span class="e-page-link">{{ $page }}</span>
                            @else
                                <a class="e-page-link" href="{{ $url }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            <li class="e-page-item {{ $paginator->hasMorePages() ? '' : 'disabled' }}">
                @if ($paginator->hasMorePages())
                    <a class="e-page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fas {{ $nextIcon }}"></i></a>
                @else
                    <span class="e-page-link"><i class="fas {{ $nextIcon }}"></i></span>
                @endif
            </li>
        </ul>
    </nav>
@endif
