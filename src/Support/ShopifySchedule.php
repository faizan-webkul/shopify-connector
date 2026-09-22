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
     * The filters as the schedule card shows them, and as they are stored. A
     * preset names its own cron expression, so the expression is filled from
     * the preset rather than left to the merchant; without a schedule it is
     * dropped, so a disabled profile keeps no expression to run later.
     *
     * @param  array<string, mixed>  $filters
     * @return array<string, mixed>
     */
    public static function fill(array $filters): array
    {
        $preset = (string) ($filters['schedule_cron_preset'] ?? self::DISABLED);

        if (isset(self::presets()[$preset])) {
            $filters['schedule_cron_expression'] = $preset;

            return $filters;
        }

        if ($preset !== self::CUSTOM) {
            unset($filters['schedule_cron_expression']);
        }

        return $filters;
    }

    /**
     * The preset values that put a profile on a schedule, Custom included.
     *
     * @return array<int, string>
     */
    public static function schedulingValues(): array
    {
        return [...array_keys(self::presets()), self::CUSTOM];
    }

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
