@props(['active'])

@php
// Custom theme classes for responsive nav links
$classes = ($active ?? false)
            ? 'block w-full ps-3 pe-4 py-2 border-l-4 border-turquoise text-start text-base font-medium text-turquoise bg-dark-navy focus:outline-none transition duration-150 ease-in-out theme-responsive-link active'
            : 'block w-full ps-3 pe-4 py-2 border-l-4 border-transparent text-start text-base font-medium text-gray-300 hover:text-white hover:bg-gray-700 focus:outline-none transition duration-150 ease-in-out theme-responsive-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
