@php
    $cardElement = $href ? 'a' : 'div';
    $cardAttributes = $href ? "href=\"{$href}\"" : '';
    $cardClasses = $href ? 'hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200 cursor-pointer' : '';
@endphp

<{{ $cardElement }} {!! $cardAttributes !!}
    class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 {{ $cardClasses }}"
@if($tooltip)
    data-tooltip="{{ $tooltip }}"
    title="{{ $tooltip }}"
@endif>
<div class="flex items-center">
    <div class="p-2 rounded-lg {{ $getColorClasses() }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $getIcon() !!}
        </svg>
    </div>
    <div class="ml-4">
        <p class="text-sm font-medium text-slate-600">{{ $title }}</p>
        <p class="text-2xl font-bold text-slate-900">{{ $value }}</p>
    </div>
</div>

@if($href)
    <div class="mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
        <svg class="w-4 h-4 text-slate-400 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </div>
@endif
</{{ $cardElement }}>
