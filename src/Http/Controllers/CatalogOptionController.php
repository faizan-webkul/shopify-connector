<?php

namespace Webkul\Shopify\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Controllers\Controller;

/**
 * Market options come from the store through Pro; without it the catalog form
 * has nothing to offer.
 */
class CatalogOptionController extends Controller
{
    public function markets(int $credentialId): JsonResponse
    {
        return new JsonResponse(['options' => []]);
    }
}
