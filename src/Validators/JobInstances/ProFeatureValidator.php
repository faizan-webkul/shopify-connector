<?php

namespace Webkul\Shopify\Validators\JobInstances;

use Illuminate\Validation\ValidationException;
use Webkul\DataTransfer\Validators\JobInstances\Default\JobValidator;
use Webkul\Shopify\Support\ProFeatures;

/**
 * Refuses a job the connector only advertises, so one is never saved against a
 * package the store does not have.
 */
class ProFeatureValidator extends JobValidator
{
    protected array $rules = [
        'filters.credentials' => 'required|integer|min:0',
    ];

    public function validate(array $data, array $options = []): void
    {
        if (! resolve(ProFeatures::class)->isInstalled()) {
            throw ValidationException::withMessages([
                'entity_type' => trans('shopify::app.shopify.pro.catalogs-note'),
            ]);
        }

        parent::validate($data, $options);
    }
}
