{{--
    The offer on its own, for a screen that has nothing to fade behind it.
--}}
@unless (resolve(\Webkul\Shopify\Support\ProFeatures::class)->isInstalled())
    <div class="shopify-pro-offer">
        <p>@lang('shopify::app.shopify.pro.tagline')</p>

        <x-shopify::pro-cta />
    </div>
@endunless
