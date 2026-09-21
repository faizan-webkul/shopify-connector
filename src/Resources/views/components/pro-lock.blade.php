{{--
    Shows what Pro would do with a card and puts the ask over it: the controls
    stay on screen, faded and inert, and the offer sits in the middle. Once Pro
    is installed the card is simply itself.
--}}
@if (resolve(\Webkul\Shopify\Support\ProFeatures::class)->isInstalled())
    {{ $slot }}
@else
    <div class="shopify-pro-lock">
        <div class="shopify-pro-lock__body" aria-hidden="true">
            {{ $slot }}
        </div>

        <div class="shopify-pro-lock__offer">
            <p>@lang('shopify::app.shopify.pro.tagline')</p>

            <x-shopify::pro-cta />
        </div>
    </div>
@endif
