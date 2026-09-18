{{--
    What the Pro package adds, named on one page for the stores running without
    it. Each card is the same note the feature's own screen shows.
--}}
<x-admin::layouts>
    <x-slot:title>
        @lang('shopify::app.shopify.pro.upgrade')
    </x-slot>

    <div class="mb-2.5">
        <x-admin::breadcrumbs />
    </div>

    <div class="flex items-center gap-2">
        <p class="text-xl font-bold text-gray-800 dark:text-slate-50">
            @lang('shopify::app.shopify.pro.upgrade-title')
        </p>

        <x-shopify::pro-badge />
    </div>

    <p class="mt-1.5 text-sm text-gray-600 dark:text-gray-300">
        @lang('shopify::app.shopify.pro.upgrade-intro')
    </p>

    <div class="mt-3.5 flex flex-col gap-2">
        @foreach ([
            ['catalogs', 'catalogs-note'],
            ['realtime', 'realtime-note'],
            ['schedule', 'schedule-note'],
            ['external-media', 'media-note'],
            ['association-mapping', 'association-note'],
            ['export-filters', 'filters-note'],
            ['attribute-conditions', 'conditions-note'],
            ['metafield-types', 'types-note'],
        ] as [$feature, $note])
            <x-shopify::pro-note
                :title="trans('shopify::app.shopify.pro.'.$feature)"
                :note="trans('shopify::app.shopify.pro.'.$note)"
            />
        @endforeach
    </div>
</x-admin::layouts>
