<?php

namespace App\Import\Utils;

use DateInterval;
use DateTimeImmutable;

class FifaDateTransformer
{
    public static function getFifaDate(?int $year = 1582, ?int $month = 10, ?int $day = 14): DateTimeImmutable
    {
        $year = $year ?? 1582;
        $month = $month ?? 10;
        $day = $day ?? 14;

        return new DateTimeImmutable($year.'-'.$month.'-'.$day);
    }

    public static function transformDaysToDate(int $days)
    {
        return self::getFifaDate()
            ->add(new DateInterval('P'.$days.'D'));
    }

    public static function transformToDate(int $fifaDate): DateTimeImmutable
    {
        $length = strlen((string) $fifaDate);

        switch ($length) {
            case 4:
                return self::getFifaDate($fifaDate);

            case 8:
                return DateTimeImmutable::createFromFormat('Ymd', $fifaDate);

            default:
                return new DateTimeImmutable('2017-07-01');
        }
    }
}
