<?php

namespace Webkul\Shopify\Support;

/**
 * The values a scheduled export is configured with. The connector renders the
 * fields; the Pro package reads these same values when it runs them.
 */
class ShopifySchedule
{
    public const DISABLED = 'disabled';

    public const CUSTOM = 'custom';

    public const RECURRING = 'recurring';

    public const ONE_TIME = 'one_time';

    /**
     * Ready made expressions, keyed by the label they are named with.
     *
     * @return array<string, string>
     */
    public static function presets(): array
    {
        return [
            '* * * * *'    => 'every-minute',
            '*/5 * * * *'  => 'every-5-minutes',
            '*/15 * * * *' => 'every-15-minutes',
            '*/30 * * * *' => 'every-30-minutes',
            '0 * * * *'    => 'hourly',
            '0 0 * * *'    => 'daily-midnight',
            '0 6 * * *'    => 'daily-6am',
            '0 0 * * 1'    => 'weekly-monday',
            '0 0 1 * *'    => 'monthly',
        ];
    }
}
