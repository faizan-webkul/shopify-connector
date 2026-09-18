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

        <x-shopify::pro-badge />
    </div>

    @unless ($shopifyProInstalled)
        <p class="mb-4 text-sm text-gray-600 dark:text-gray-300">
            @lang('shopify::app.shopify.pro.schedule-note')
        </p>
    @endunless

    <fieldset @disabled(! $shopifyProInstalled)>
        <x-admin::data-transfer.filter-fields
            ::entity-type="entityType"
            :exporter-config="config('exporters')"
            only="schedule_cron_preset,schedule_cron_expression,schedule_timezone,schedule_type"
            grid-class="grid grid-cols-1"
        />
    </fieldset>
</div>

@include('shopify::data-transfer._cron-field')
