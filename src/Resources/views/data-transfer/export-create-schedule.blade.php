{{--
    The schedule card on the export create screen. The entity type is still
    being picked there, so the card follows the live selection.
--}}
<div
    v-if="filterFields.some(field => field.name === 'schedule_cron_preset')"
    class="p-4 bg-white dark:bg-cherry-900 rounded box-shadow"
>
    <div class="flex items-center gap-2 mb-4">
        <p class="text-base text-gray-800 dark:text-white font-semibold">
            @lang('shopify::app.export.schedule.title')
        </p>

        <x-shopify::pro-cta variant="badge" />
    </div>

    <x-shopify::pro-lock>
        <fieldset @disabled(! $shopifyProInstalled)>
            <x-admin::data-transfer.filter-fields
                ::entity-type="entityType"
                :exporter-config="config('exporters')"
                only="schedule_cron_preset,schedule_cron_expression,schedule_timezone,schedule_type"
                grid-class="grid grid-cols-1"
            />
        </fieldset>
    </x-shopify::pro-lock>
</div>

@include('shopify::data-transfer._cron-field')
