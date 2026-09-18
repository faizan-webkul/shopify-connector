<?php

namespace Webkul\Shopify\Http\Controllers;

use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class UpgradeController extends Controller
{
    /**
     * Name what the Pro package adds, for the stores running without it.
     */
    public function index(): View
    {
        return view('shopify::upgrade.index');
    }
}
