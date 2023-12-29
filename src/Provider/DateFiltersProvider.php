<?php

declare(strict_types=1);

namespace Oksydan\IsFavoriteProducts\Provider;

class DateFiltersProvider implements DateFiltersProviderInterface
{
    public const ALL_TIME = 'all_time';

    public const LAST_24_HOURS = 'last_24_hours';

    public const LAST_3_DAYS = 'last_3_days';

    public const LAST_7_DAYS = 'last_7_days';

    public const LAST_30_DAYS = 'last_30_days';

    public const LAST_90_DAYS = 'last_90_days';

    public function getDateFilters(): array
    {
        return [
            self::ALL_TIME,
            self::LAST_24_HOURS,
            self::LAST_3_DAYS,
            self::LAST_7_DAYS,
            self::LAST_30_DAYS,
            self::LAST_90_DAYS,
        ];
    }
}
