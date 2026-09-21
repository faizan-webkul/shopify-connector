{{--
    The mapping screen carries two Pro cards side by side, so they sit behind
    one veil and share the single ask rather than repeating it.
--}}
<x-shopify::pro-lock>
    <div class="flex flex-col gap-4">
        @include('shopify::external-media.section')

        @include('shopify::association-mappings.section')
    </div>
</x-shopify::pro-lock>
