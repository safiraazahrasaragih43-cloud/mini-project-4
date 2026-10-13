@props(['type' => 'info'])

@php
    $classMap = [
        'success' => 'alert-success',
        'error' => 'alert-danger',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info',
    ];
    $alertClass = $classMap[$type] ?? 'alert-info';
@endphp

<div class="alert {{ $alertClass }}">
    {{ $slot }}
</div>
