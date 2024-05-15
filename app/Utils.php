<?php

namespace App;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;

class Utils{

    public static function getDateRange(Carbon $start, Carbon $end): array {
        $period = new DatePeriod(
            $start,
            new DateInterval('P1D'),
            $end
        );
    
        $dateRange = [];
        foreach ($period as $date) {
            $dateRange[] = $date->format('Y-m-d');
        }
    
        $dateRange[] = $end->format('Y-m-d');
        return $dateRange;
    }
    
}
