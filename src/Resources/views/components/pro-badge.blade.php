@php
    $installed = resolve(\Webkul\Shopify\Support\ProFeatures::class)->isInstalled();
@endphp

<x-admin::badge
    :variant="$installed ? 'info' : 'warning'"
    {{ $attributes->merge(['class' => 'shopify-pro-badge shrink-0']) }}
>
    {{ $installed ? trans('shopify::app.shopify.pro.badge') : trans('shopify::app.shopify.pro.upgrade') }}
</x-admin::badge>
