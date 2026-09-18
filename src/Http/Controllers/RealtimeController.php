<?php

namespace Webkul\Shopify\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Shopify\Repositories\ShopifyCredentialRepository;

/**
 * The real-time sync screens as the connector alone can serve them: the design
 * is here, while the Pro package binds the implementation that pushes products
 * and stores the settings over this one.
 */
class RealtimeController extends Controller
{
    public function __construct(protected ShopifyCredentialRepository $credentialRepository) {}

    public function index(): View
    {
        return view('shopify::realtime.index', ['realtimeSettings' => ['channel' => null, 'currency' => null]]);
    }

    public function credential(int $credentialId): View
    {
        return view('shopify::realtime.credential', [
            'credential' => $this->credentialRepository->findOrFail($credentialId),
            'enabled'    => false,
            'blocker'    => null,
        ]);
    }

    public function store(): JsonResponse
    {
        return $this->unavailable();
    }

    public function toggle(int $credentialId): JsonResponse
    {
        return $this->unavailable();
    }

    /**
     * Real-time sync is run by Pro, so without it there is nothing to store.
     */
    protected function unavailable(): JsonResponse
    {
        return new JsonResponse([
            'message' => trans('shopify::app.shopify.pro.realtime-note'),
        ], 403);
    }
}
