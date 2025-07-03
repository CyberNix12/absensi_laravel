@props([
    'title' => 'Total Presensi',
    'text' => '',
    'icon' => 'fas fa-user',
    'variant' => 'primary',
    'url' => '#',
])
@php
    $bg_color = match ($variant) {
        'primary' => [
            'bg_base' => '!bg-primary',
            'bg_icon' => '!bg-primary-content !text-primary',
        ],
        'secondary' => [
            'bg_base' => '!bg-secondary',
            'bg_icon' => '!bg-secondary-content !text-secondary',
        ],
        'success' => [
            'bg_base' => '!bg-accent',
            'bg_icon' => '!bg-accent-content text-accent',
        ],
        'info' => [
            'bg_base' => '!bg-info',
            'bg_icon' => '!bg-info-content text-info',
        ],
        'warning' => [
            'bg_base' => '!bg-warning',
            'bg_icon' => '!bg-warning-content text-warning',
        ],
        'danger' => [
            'bg_base' => '!bg-error',
            'bg_icon' => '!bg-error-content text-error',
        ],
        default => [
            'bg_base' => '!bg-base-100',
            'bg_icon' => '!bg-base-200 text-base-content',
        ],
    };
@endphp

<div class="card {{ $bg_color['bg_base'] }} hover:shadow-lg transition duration-200 ease-in-out !border-0">
    <div class="card-body">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-lg {{ $bg_color['bg_icon'] }}">
                <i class="{{ $icon }}"></i>
            </div>
            <div class="flex flex-col">
                <span class="text-2xl font-bold text-white">{{ $title }}</span>
                @if ($text != '')
                    <span class="mt-1 text-md font-semibold text-white">{{ $text }}</span>
                @else
                    <a href="{{ $url }}" class="btn btn-primary-content btn-sm">
                        <i class="fas fa-camera"></i> Presensi Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>