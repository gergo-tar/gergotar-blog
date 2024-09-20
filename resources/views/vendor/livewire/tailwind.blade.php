@php
if (! isset($scrollTo)) {
    $scrollTo = 'body';
}

$scrollIntoViewJsSnippet = ($scrollTo !== false)
    ? <<<JS
       (\$el.closest('{$scrollTo}') || document.querySelector('{$scrollTo}')).scrollIntoView()
    JS
    : '';
@endphp

<div>
    @if ($paginator->hasPages())
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="flex justify-between flex-1 sm:hidden">
                <span class="flex py-1">
                    @if ($paginator->onFirstPage())
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-500 bg-white border border-gray-300 rounded-md cursor-default dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>

                <span class="flex py-1">
                    @if ($paginator->hasMorePages())
                        <button type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" wire:loading.attr="disabled" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before" class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md hover:text-gray-500 focus:outline-none focus:ring ring-blue-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:focus:border-blue-700 dark:active:bg-gray-700 dark:active:text-gray-300">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium leading-5 text-gray-500 bg-white border border-gray-300 rounded-md cursor-default dark:text-gray-600 dark:bg-gray-800 dark:border-gray-600">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                {{-- <div>
                    <p class="text-sm leading-5 text-gray-700 dark:text-gray-400">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div> --}}

                <div class="flex pt-8 lg:pt-16">
                    <span class="relative z-0 inline-flex rounded-md shadow-sm rtl:flex-row-reverse">
                        {{-- Previous Page Link --}}
                        @if (! $paginator->onFirstPage())
                            <span class="flex py-1">
                                <span  wire:click="previousPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="flex items-center px-3 py-1 ml-3 font-medium transition-colors border-2 cursor-pointer group border-primary font-body text-primary hover:border-secondary hover:text-secondary dark:border-green-light dark:text-white dark:hover:border-secondary dark:hover:text-secondary" aria-label="{{ __('pagination.previous') }}">
                                    <i class="ml-1 transition-colors bx bx-left-arrow-alt text-primary group-hover:text-secondary dark:text-white"></i>
                                    {{ __('pagination.previous') }}
                                </span>
                            </span>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium leading-5 text-gray-700 bg-white border border-gray-300 cursor-default dark:bg-gray-800 dark:border-gray-600">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}" class="flex py-1">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page" class="px-3 py-1 font-medium border-2 cursor-pointer border-secondary font-body text-secondary{{ ! $paginator->onFirstPage() ? ' ml-3' : '' }}">{{ $page }}</span>
                                        @else
                                            <span wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" class="px-3 py-1 ml-3 font-medium transition-colors border-2 cursor-pointer border-primary font-body text-primary hover:border-secondary hover:text-secondary dark:border-green-light dark:text-white dark:hover:border-secondary dark:hover:text-secondary" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                                {{ $page }}
                                            </span>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($paginator->hasMorePages())
                            <span class="flex py-1">
                                <span wire:click="nextPage('{{ $paginator->getPageName() }}')" x-on:click="{{ $scrollIntoViewJsSnippet }}" dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after" class="flex items-center px-3 py-1 ml-3 font-medium transition-colors border-2 cursor-pointer group border-primary font-body text-primary hover:border-secondary hover:text-secondary dark:border-green-light dark:text-white dark:hover:border-secondary dark:hover:text-secondary" aria-label="{{ __('pagination.next') }}">
                                    {!! __('pagination.next') !!}
                                    <i class="ml-1 transition-colors bx bx-right-arrow-alt text-primary group-hover:text-secondary dark:text-white"></i>
                                </span>
                            </span>
                        @endif
                </div>
            </div>
        </nav>
    @endif
</div>
