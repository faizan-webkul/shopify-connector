<?php

namespace Webkul\Shopify\Helpers\Exporters\Pro;

use Webkul\DataTransfer\Contracts\JobTrackBatch as JobTrackBatchContract;
use Webkul\DataTransfer\Helpers\Exporters\AbstractExporter;
use Webkul\Shopify\Exceptions\ProFeatureUnavailable;

/**
 * Stands in for an export only Shopify Pro can run, so the job type is offered
 * on the screens while the run itself says what is missing.
 */
class UnavailableExporter extends AbstractExporter
{
    protected bool $exportsFile = false;

    public function exportBatch(JobTrackBatchContract $exportBatchContract, $filePath): bool
    {
        throw new ProFeatureUnavailable(trans('shopify::app.shopify.pro.catalogs-note'));
    }
}
