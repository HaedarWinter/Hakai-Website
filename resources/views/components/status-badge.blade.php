@props(['status'])

@php
    $statusConfig = [
        'approved' => [
            'text' => 'Disetujui',
            'class' => 'badge-success',
            'icon' => 'fa-check-circle'
        ],
        'submitted' => [
            'text' => 'Menunggu Persetujuan',
            'class' => 'badge-info',
            'icon' => 'fa-clock'
        ],
        'rejected' => [
            'text' => 'Ditolak',
            'class' => 'badge-danger',
            'icon' => 'fa-times-circle'
        ],
        'pending' => [
            'text' => 'Pending',
            'class' => 'badge-warning',
            'icon' => 'fa-hourglass-half'
        ]
    ];
    
    $config = $statusConfig[$status] ?? $statusConfig['pending'];
@endphp

<span class="badge {{ $config['class'] }}">
    <i class="fas {{ $config['icon'] }}"></i> {{ $config['text'] }}
</span>

