<?php

namespace Webkul\Shopify\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Shopify\Repositories\ShopifyCredentialRepository;

/**
 * The catalog screens as the connector alone can serve them: the design is
 * here, while the Pro package binds the implementation that reads and writes
 * catalogs over this one.
 */
class CatalogController extends Controller
{
    public function __construct(protected ShopifyCredentialRepository $credentialRepository) {}

    public function index(int $credentialId): View
    {
        return view('shopify::catalogs.index', [
            'credential' => $this->credentialRepository->findOrFail($credentialId),
        ]);
    }

    public function store(int $credentialId): JsonResponse
    {
        return $this->unavailable();
    }

    public function edit(int $credentialId, int $id): JsonResponse
    {
        return $this->unavailable();
    }

    public function update(int $credentialId, int $id): JsonResponse
    {
        return $this->unavailable();
    }

    public function destroy(int $credentialId, int $id): JsonResponse
    {
        return $this->unavailable();
    }

    public function massDestroy(int $credentialId): JsonResponse
    {
        return $this->unavailable();
    }

    /**
     * Catalogs are stored by Pro, so without it there is nothing to act on.
     */
    protected function unavailable(): JsonResponse
    {
        return new JsonResponse([
            'message' => trans('shopify::app.shopify.pro.catalogs-note'),
        ], 403);
    }
}
