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

        if ($now->dayOfWeek <= Carbon::FRIDAY && $now->hour < 15) {
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
}