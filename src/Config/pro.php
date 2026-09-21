<?php

return [
    /**
     * Where a store running without the Pro package is sent to get it. Every
     * screen links straight here, so the destination changes in one place.
     */
    'url' => 'https://unopim.com/extensions',

    /**
     * The job types only Pro can run. Without it they are left out of the
     * pickers rather than offered and refused.
     */
    'jobs' => [
        'exporters' => ['shopifyCatalog'],
        'importers' => ['shopifyCatalog', 'shopifyCatalogPrice'],
    ],
];
