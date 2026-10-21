@props(['title', 'value' => 0, 'icon', 'color' => 'primary', 'id' => null])

@php
    $colorMap = [
        'primary' => 'border-left-primary',
        'success' => 'border-left-success',
        'info' => 'border-left-info',
        'warning' => 'border-left-warning',
        'danger' => 'border-left-danger',
    ];

    $borderClass = $colorMap[$color] ?? 'border-left-primary';
@endphp

@php
    $glowMap = [
        'primary' => 'glow-primary',
        'success' => 'glow-success',
        'info' => 'glow-info',
        'warning' => 'glow-warning',
        'danger' => 'glow-danger',
    ];

    $glowClass = $glowMap[$color] ?? 'glow-primary';
@endphp

<div class="col-xl-3 col-md-6 mb-4 animate-in">
    <div class="premium-card {{ $glowClass }} h-100">
        <div class="stat-card-v2">
            <div class="stat-icon-box bg-{{ $color }} shadow-sm text-white">
                <i class="fas {{ $icon }}"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label text-{{ $color }}">{{ $title }}</div>
                <div class="stat-value" {!! $id ? 'id="' . $id . '"' : '' !!}>{{ $value }}</div>
            </div>
        </div>
        <!-- Decorative subtle accent bar -->
        <div class="bg-{{ $color }}"
            style="height: 4px; width: 40px; margin-left: 1.5rem; border-radius: 2px; opacity: 0.5; margin-bottom: 1rem;">
        </div>
    </div>
</div>