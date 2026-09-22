<?php

use Webkul\Shopify\Support\ShopifySchedule;

$presets = ShopifySchedule::presets();

$scheduled = [
    'field'  => 'schedule_cron_preset',
    'values' => ShopifySchedule::schedulingValues(),
];

return [
    'entity_types' => [
        'shopifyProduct',
        'shopifyCategories',
        'shopifyMetafield',
        'shopifyMetaobject',
        'shopifyCatalog',
    ],

    'fields' => [
        [
            'name'     => 'schedule_cron_preset',
            'title'    => 'shopify::app.export.schedule.preset',
            'info'     => 'shopify::app.export.schedule.preset-info',
            'required' => false,
            'type'     => 'select',
            'options'  => [
                ['label' => 'shopify::app.export.schedule.presets.disabled', 'value' => ShopifySchedule::DISABLED],
                ...array_map(
                    fn (string $expression, string $key): array => [
                        'label' => 'shopify::app.export.schedule.presets.'.$key,
                        'value' => $expression,
                    ],
                    array_keys($presets),
                    $presets
                ),
                ['label' => 'shopify::app.export.schedule.presets.custom', 'value' => ShopifySchedule::CUSTOM],
            ],
        ], [
            'name'         => 'schedule_cron_expression',
            'title'        => 'shopify::app.export.schedule.cron',
            'info'         => 'shopify::app.export.schedule.cron-info',
            'required'     => true,
            'validation'   => 'required',
            'type'         => 'cron',
            'placeholder'  => '* * * * *',
            'visible_when' => $scheduled,
            'depends_on'   => ['field' => 'schedule_cron_preset', 'as' => 'preset'],
        ], [
            'name'         => 'schedule_timezone',
            'title'        => 'shopify::app.export.schedule.timezone',
            'required'     => false,
            'type'         => 'select',
            'options'      => array_map(
                fn (string $timezone): array => ['label' => $timezone, 'value' => $timezone],
                timezone_identifiers_list()
            ),
            'visible_when' => $scheduled,
        ], [
            'name'         => 'schedule_type',
            'title'        => 'shopify::app.export.schedule.type',
            'info'         => 'shopify::app.export.schedule.type-info',
            'required'     => false,
            'type'         => 'select',
            'options'      => [
                ['label' => 'shopify::app.export.schedule.types.recurring', 'value' => ShopifySchedule::RECURRING],
                ['label' => 'shopify::app.export.schedule.types.one-time', 'value' => ShopifySchedule::ONE_TIME],
            ],
            'visible_when' => $scheduled,
        ],
    ],
];
