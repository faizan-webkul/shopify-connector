{{--
    The two marks a Pro feature wears: the badge that names it, and the button
    that sells it. The button is the only place the offer is spelled out, so it
    is never repeated beside the badge.
--}}
@props(['variant' => 'button', 'label' => null])

@php
    $proFeatures = resolve(\Webkul\Shopify\Support\ProFeatures::class);
    $installed = $proFeatures->isInstalled();
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
        {{ $attributes->merge(['class' => 'shopify-pro-cta shrink-0']) }}
    >
        {{ $label ?? trans('shopify::app.shopify.pro.upgrade') }}
    </a>
@endif
