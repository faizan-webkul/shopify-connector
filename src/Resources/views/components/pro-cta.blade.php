{{--
    The marks a Pro feature wears: the badge that names it, and the button that
    sells it, in the wording the spot calls for.
--}}
@props(['variant' => 'button'])

@php
    $proFeatures = resolve(\Webkul\Shopify\Support\ProFeatures::class);
    $installed = $proFeatures->isInstalled();

    $labels = [
        'button' => 'shopify::app.shopify.pro.upgrade',
        'unlock' => 'shopify::app.shopify.pro.unlock',
    ];
@endphp

@if ($variant === 'badge')
    <x-admin::badge
        :variant="$installed ? 'info' : 'warning'"
        {{ $attributes->merge(['class' => 'shopify-pro-badge shrink-0']) }}
    >
        {{ trans('shopify::app.shopify.pro.badge') }}
    </x-admin::badge>
@elseif (! $installed)
    <a
        href="{{ $proFeatures->upgradeUrl() }}"
        target="_blank"
        rel="noopener noreferrer"
        {{ $attributes->merge(['class' => 'shrink-0 '.($variant === 'unlock' ? 'shopify-pro-unlock' : 'shopify-pro-cta')]) }}
    >
        {{ trans($labels[$variant] ?? $labels['button']) }}
    </a>
@endif
