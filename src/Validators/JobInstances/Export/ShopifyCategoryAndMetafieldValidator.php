<?php

namespace Webkul\Shopify\Validators\JobInstances\Export;

use Webkul\DataTransfer\Validators\JobInstances\Default\JobValidator;
use Webkul\Shopify\Validators\JobInstances\ValidatesShopifySchedule;

class ShopifyCategoryAndMetafieldValidator extends JobValidator
{
    use ValidatesShopifySchedule;

    protected array $rules = [
        'filters.credentials' => 'required|integer|min:0',
    ];

    protected array $attributeNames = [
        'filters.credentials' => 'Credentials',
    ];

    public function getValidatorRule(): array
    {
        return $this->rules;
    }
}
