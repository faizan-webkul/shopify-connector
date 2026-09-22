<?php

namespace Webkul\Shopify\Validators\JobInstances;

use Webkul\Shopify\Support\ShopifySchedule;

/**
 * The schedule fields the connector appends to every export that may run
 * unattended. An expression is only asked for once a schedule is picked: a
 * preset fills its own, Custom is typed, and without a schedule the field is
 * not shown and not required.
 */
trait ValidatesShopifySchedule
{
    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function getRules(array $options): array
    {
        return array_merge(parent::getRules($options), [
            'filters.schedule_cron_preset'     => 'nullable|string',
            'filters.schedule_cron_expression' => 'nullable|string|required_if:filters.schedule_cron_preset,'.implode(',', ShopifySchedule::schedulingValues()),
            'filters.schedule_timezone'        => 'nullable|timezone',
            'filters.schedule_type'            => 'nullable|in:'.ShopifySchedule::RECURRING.','.ShopifySchedule::ONE_TIME,
        ]);
    }

    /**
     * Normalise the schedule the same way it is stored, so a preset is
     * validated against the expression it names and a disabled profile is not
     * held to an expression it no longer carries.
     */
    public function preValidationProcess(mixed $data): mixed
    {
        $data = parent::preValidationProcess($data);

        if (is_array($data) && is_array($data['filters'] ?? null)) {
            $data['filters'] = ShopifySchedule::fill($data['filters']);
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $options
     * @return array<string, mixed>
     */
    public function getAttributeNames(array $options): array
    {
        return array_merge(parent::getAttributeNames($options), [
            'filters.schedule_cron_preset'     => trans('shopify::app.export.schedule.preset'),
            'filters.schedule_cron_expression' => trans('shopify::app.export.schedule.cron'),
            'filters.schedule_timezone'        => trans('shopify::app.export.schedule.timezone'),
            'filters.schedule_type'            => trans('shopify::app.export.schedule.type'),
        ]);
    }
}
