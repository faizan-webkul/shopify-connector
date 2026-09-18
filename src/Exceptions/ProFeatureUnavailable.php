<?php

namespace Webkul\Shopify\Exceptions;

use Exception;

/**
 * Raised when a job type the connector only advertises is actually run.
 */
class ProFeatureUnavailable extends Exception {}
