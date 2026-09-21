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

            <x-shopify::pro-cta variant="badge" />
        </div>

        <x-shopify::pro-lock>
            <fieldset @disabled(! $shopifyProInstalled)>
                <x-admin::data-transfer.filter-fields
                    :entity-type="$shopifyEntityType"
                    :values="$shopifyExport?->filters ?? []"
                    :exporter-config="config('exporters')"
                    :only="$shopifySchedule->implode(',')"
                    grid-class="grid grid-cols-1"
                />
            </fieldset>
        </x-shopify::pro-lock>
    </div>

    @include('shopify::data-transfer._cron-field')
@endif
