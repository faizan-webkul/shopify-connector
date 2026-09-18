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
        <div class="flex items-center gap-2 mb-4">
            <p class="text-base text-gray-800 dark:text-white font-semibold">
                @lang('shopify::app.export.schedule.title')
            </p>

            <x-shopify::pro-badge />
        </div>

        @unless ($shopifyProInstalled)
            <p class="mb-4 text-sm text-gray-600 dark:text-gray-300">
                @lang('shopify::app.shopify.pro.schedule-note')
            </p>
        @endunless

        <fieldset @disabled(! $shopifyProInstalled)>
            <x-admin::data-transfer.filter-fields
                :entity-type="$shopifyEntityType"
                :values="$shopifyExport?->filters ?? []"
                :exporter-config="config('exporters')"
                :only="$shopifySchedule->implode(',')"
                grid-class="grid grid-cols-1"
            />
        </fieldset>
    </div>

    @include('shopify::data-transfer._cron-field')
@endif
