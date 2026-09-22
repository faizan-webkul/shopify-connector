{{--
    When a Shopify export may run unattended, the schedule it keeps. The Pro
    package runs it; here it is only offered, and stays read only without it.
--}}
@php
    $shopifyExport = app(\Webkul\DataTransfer\Repositories\JobInstancesRepository::class)->find(request()->route('id'));
    $shopifyEntityType = $shopifyExport?->entity_type;

    $shopifySchedule = collect(config("exporters.{$shopifyEntityType}.filters.fields", []))
        ->pluck('name')
        ->intersect(['schedule_cron_preset', 'schedule_cron_expression', 'schedule_timezone', 'schedule_type'])
        ->values();
@endphp

@if ($shopifySchedule->isNotEmpty())
    <div class="p-4 bg-white dark:bg-cherry-900 rounded box-shadow">
        <x-shopify::pro-notice
            class="shopify-pro-notice--bare"
            :title="trans('shopify::app.export.schedule.title')"
            :note="trans('shopify::app.shopify.pro.schedule-note')"
        />

        <fieldset @disabled(! $shopifyProInstalled)>
            <x-admin::data-transfer.filter-fields
                :entity-type="$shopifyEntityType"
                :values="\Webkul\Shopify\Support\ShopifySchedule::fill($shopifyExport?->filters ?? [])"
                :exporter-config="config('exporters')"
                :only="$shopifySchedule->implode(',')"
                grid-class="grid grid-cols-1"
            />
        </fieldset>
    </div>

    @include('shopify::data-transfer._cron-field')
@endif
