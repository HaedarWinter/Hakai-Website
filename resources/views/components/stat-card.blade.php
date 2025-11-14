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

<div class="col-xl-3 col-md-6 mb-4">
    <div class="card {{ $borderClass }} shadow h-100 py-2">
        <div class="card-body">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-{{ $color }} text-uppercase mb-1">{{ $title }}</div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800" {!! $id ? 'id="'.$id.'"' : '' !!}>{{ $value }}</div>
                </div>
                <div class="col-auto">
                    <i class="fas {{ $icon }} fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
    </div>
</div>

