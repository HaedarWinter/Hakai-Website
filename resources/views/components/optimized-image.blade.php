@props([
    'src',
    'alt' => '',
    'class' => '',
    'loading' => 'lazy',
    'width' => null,
    'height' => null,
    'fallback' => null
])

@php
    // Ensure alt text is always provided (SEO & Accessibility requirement)
    $altText = !empty($alt) ? $alt : 'Image';
    
    // Default fallback if image fails to load
    $fallbackImage = $fallback ?? asset('sbadmin2/img/undraw_profile.svg');
    
    // Generate srcset for responsive images if width is provided
    $srcset = null;
    if ($width && is_numeric($width)) {
        $srcset = "$src {$width}w";
    }
@endphp

<img 
    src="{{ $src }}" 
    alt="{{ $altText }}"
    @if($class) class="{{ $class }}" @endif
    @if($loading) loading="{{ $loading }}" @endif
    @if($width) width="{{ $width }}" @endif
    @if($height) height="{{ $height }}" @endif
    @if($srcset) srcset="{{ $srcset }}" @endif
    onerror="this.onerror=null; this.src='{{ $fallbackImage }}';"
    {{ $attributes }}
/>

