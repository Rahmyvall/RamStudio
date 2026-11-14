@if ($paginator->hasPages())
     <nav aria-label="Navigasi halaman">
          <ul class="pagination">
               {{-- Previous --}}
               @if ($paginator->onFirstPage())
                    <li class="disabled"><span>&lsaquo;</span></li>
               @else
                    <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Sebelumnya">&lsaquo;</a>
                    </li>
               @endif

               {{-- Page numbers, collapsing long ranges --}}
               @php
                    $current = $paginator->currentPage();
                    $last = $paginator->lastPage();
                    $window = 1;
                    $pages = [];

                    for ($page = 1; $page <= $last; $page++) {
                        if ($page === 1 || $page === $last || abs($page - $current) <= $window) {
                            $pages[] = $page;
                            continue;
                        }

                        if (end($pages) !== '...') {
                            $pages[] = '...';
                        }
                    }
               @endphp

               @foreach ($pages as $page)
                    @if ($page === '...')
                         <li class="disabled"><span>&hellip;</span></li>
                    @elseif ($page === $current)
                         <li class="active"><span aria-current="page">{{ $page }}</span></li>
                    @else
                         <li><a href="{{ $paginator->url($page) }}">{{ $page }}</a></li>
                    @endif
               @endforeach

               {{-- Next --}}
               @if ($paginator->hasMorePages())
                    <li><a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Berikutnya">&rsaquo;</a>
                    </li>
               @else
                    <li class="disabled"><span>&rsaquo;</span></li>
               @endif
          </ul>
     </nav>
@endif
