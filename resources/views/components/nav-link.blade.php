@props(['active'])

@php
// The base classes define the link's layout, font, and transition properties.
$baseClasses = 'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out';

// The 'theme-nav-link' class is now used for both states.
// We conditionally add the 'active' class, which is then targeted by the CSS in your main navigation file to apply the turquoise accent color.
$themeClasses = ($active ?? false)
            ? 'theme-nav-link active'
            : 'theme-nav-link';

// The final classes are a combination of the base layout and the theme classes.
$classes = $baseClasses . ' ' . $themeClasses;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>