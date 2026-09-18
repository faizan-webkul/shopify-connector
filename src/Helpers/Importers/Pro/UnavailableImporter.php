<?php

namespace Webkul\Shopify\Helpers\Importers\Pro;

use Webkul\DataTransfer\Contracts\JobTrackBatch as ImportJobBatchContract;
use Webkul\DataTransfer\Helpers\Importers\AbstractImporter;
use Webkul\Shopify\Exceptions\ProFeatureUnavailable;

/**
 * Stands in for an import only Shopify Pro can run.
 */
class UnavailableImporter extends AbstractImporter
{
    public function validateRow(array $rowData, int $rowNumber): bool
    {
        throw new ProFeatureUnavailable(trans('shopify::app.shopify.pro.catalogs-note'));
    }

    public function importBatch(ImportJobBatchContract $importBatchContract): bool
    {
        throw new ProFeatureUnavailable(trans('shopify::app.shopify.pro.catalogs-note'));
    }
}
