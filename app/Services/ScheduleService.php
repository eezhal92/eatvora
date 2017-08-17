<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;

class ScheduleService
{
    /**
     * Get Next Week Day Dates
     * @return Collection
     */
    public function nextWeekDayDates()
    {
        $now = Carbon::now();

        // Set to Friday 3pm
        $weekBoundary = $now->copy()->startOfWeek()->addDay(4)->setTime(15, 00, 00);

        if ($now->lt($weekBoundary)) {
            $nextMonday = $now->copy()->addWeek()->startOfWeek();

            return collect([
                $nextMonday,
                $nextMonday->copy()->addDay(1),
                $nextMonday->copy()->addDay(2),
                $nextMonday->copy()->addDay(3),
                $nextMonday->copy()->addDay(4),
            ]);
        }

        $nextMonday = $now->copy()->addWeek(2)->startOfWeek();

        return collect([
            $nextMonday,
            $nextMonday->copy()->addDay(1),
            $nextMonday->copy()->addDay(2),
            $nextMonday->copy()->addDay(3),
            $nextMonday->copy()->addDay(4),
        ]);
    }

    public function nextWeekDaysRange()
    {
        $dates =  $this->nextWeekDayDates()->map->format('Y-m-d');

        return [$dates->first(), $dates->last()];
    }
}
