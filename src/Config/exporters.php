<?php

return [
    'shopifyProduct' => [
        'title'     => 'shopify::app.exporters.shopify.product',
        'exporter'  => 'Webkul\Shopify\Helpers\Exporters\Product\Exporter',
        'source'    => 'Webkul\Product\Repositories\ProductRepository',
        'validator' => 'Webkul\Shopify\Validators\JobInstances\Export\ShopifyProductValidator',
        'filters'   => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'shopify::app.shopify.job.credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'shopify.credential.fetch-all',
                ], [

                    'name'       => 'channels',
                    'title'      => 'shopify::app.shopify.job.channel',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'shopify.channel.fetch-all',
                ], [
                    'name'       => 'currencies',
                    'title'      => 'shopify::app.shopify.job.currency',
                    'required'   => true,
                    'type'       => 'select',
                    'validation' => 'required',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'shopify.currency.fetch-all',
                    'depends_on' => ['field' => 'channels', 'as' => 'channel'],
                ], [
                    'name'       => 'sku',
                    'title'      => 'data_transfer::app.exporters.products.filters.identifiers',
                    'info'       => 'data_transfer::app.exporters.products.filters.identifiers-info',
                    'required'   => false,
                    'type'       => 'tags',
                    'full_width' => true,
                ], [
                    'name'       => 'status',
                    'title'      => 'data_transfer::app.exporters.products.filters.status',
                    'required'   => false,
                    'type'       => 'select',
                    'full_width' => true,
                    'options'    => [
                        ['label' => 'data_transfer::app.exporters.products.filters.status-options.enable', 'value' => 'enable'],
                        ['label' => 'data_transfer::app.exporters.products.filters.status-options.disable', 'value' => 'disable'],
                        ['label' => 'data_transfer::app.exporters.products.filters.status-options.all', 'value' => 'all'],
                    ],
                ],
            ],
        ],
    ],

    'shopifyCategories' => [
        'title'     => 'shopify::app.exporters.shopify.category',
        'exporter'  => 'Webkul\Shopify\Helpers\Exporters\Category\Exporter',
        'source'    => 'Webkul\Category\Repositories\CategoryRepository',
        'validator' => 'Webkul\Shopify\Validators\JobInstances\Export\ShopifyCategoryAndMetafieldValidator',
        'filters'   => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'shopify::app.shopify.job.credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'shopify.credential.fetch-all',
                ],
            ],
        ],
    ],

    'shopifyMetafield' => [
        'title'     => 'shopify::app.exporters.shopify.metafields',
        'exporter'  => 'Webkul\Shopify\Helpers\Exporters\MetaField\Exporter',
        'source'    => 'Webkul\Shopify\Repositories\ShopifyMetaFieldRepository',
        'validator' => 'Webkul\Shopify\Validators\JobInstances\Export\ShopifyCategoryAndMetafieldValidator',
        'filters'   => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'Shopify credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'shopify.credential.fetch-all',
                ],
            ],
        ],
    ],

    'shopifyMetaobject' => [
        'title'     => 'shopify::app.exporters.shopify.metaobjects',
        'exporter'  => 'Webkul\Shopify\Helpers\Exporters\Metaobject\Exporter',
        'source'    => 'Webkul\Shopify\Repositories\ShopifyMetaobjectDefinitionRepository',
        'validator' => 'Webkul\Shopify\Validators\JobInstances\Export\ShopifyCategoryAndMetafieldValidator',
        'filters'   => [
            'fields' => [
                [
                    'name'       => 'credentials',
                    'title'      => 'Shopify credentials',
                    'required'   => true,
                    'validation' => 'required',
                    'type'       => 'select',
                    'async'      => true,
                    'track_by'   => 'id',
                    'label_by'   => 'label',
                    'list_route' => 'shopify.credential.fetch-all',
                ],
            ],
        ],
    ],
];
