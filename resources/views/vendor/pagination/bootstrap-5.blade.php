@if ($paginator->hasPages())
    <nav aria-label="გვერდების ნავიგაცია">
        <ul class="pagination justify-content-center flex-wrap">

            @foreach ($elements as $element)
                {{-- "..." გამყოფი --}}
                @if (is_string($element))
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- გვერდის ნომრები --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

        </ul>
    </nav>
@endif
