@if($paginator->hasPages())
<nav aria-label="Page navigation" class="mt-5">
    <ul class="pagination justify-content-center">

        <li @class(['page-item', 'disabled'=>$paginator->onFirstPage()])>
            <a class="page-link" href="{{$paginator->previousPageUrl()}}" tabindex="-1">قبلی</a>
        </li>

        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><a class="page-link" href="#">...</a></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    <li @class(['page-item', 'active'=>$page == $paginator->currentPage()])><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endforeach
            @endif

        @endforeach

        <li @class(['page-item', 'disabled'=>$paginator->onLastPage()])>
            <a class="page-link" href="{{$paginator->nextPageUrl()}}">بعدی</a>
        </li>

    </ul>
</nav>
@endif