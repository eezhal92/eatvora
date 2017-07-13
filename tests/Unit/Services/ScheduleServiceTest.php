<?php

namespace Tests\Unit\Services;

use App\Services\ScheduleService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ScheduleServiceTest extends TestCase
{
    /** @test */
    public function get_correct_next_schedule()
    {
        $service = new ScheduleService();

        // When today is less than Friday 3 PM
        $pretendedCurrentDate = Carbon::create(2017, 6, 9, 15);

        Carbon::setTestNow($pretendedCurrentDate);

        $nextWeekDayDates = $service->nextWeekDayDates()->map(function ($day) {
            return $day->format('Y-m-d');
        });

        $this->assertEquals([
            '2017-06-19',
            '2017-06-20',
            '2017-06-21',
            '2017-06-22',
            '2017-06-23',
        ], $nextWeekDayDates->toArray());

        // When today is less than Friday 3 PM
        $pretendedCurrentDate = Carbon::create(2017, 6, 12);

        Carbon::setTestNow($pretendedCurrentDate);

        $nextWeekDayDates = (new ScheduleService())->nextWeekDayDates()->map(function ($day) {
            return $day->format('Y-m-d');
        });

        $this->assertEquals([
            '2017-06-19',
            '2017-06-20',
            '2017-06-21',
            '2017-06-22',
            '2017-06-23',
        ], $nextWeekDayDates->toArray());

        // Set to Friday before 3 PM
        $pretendedCurrentDate = Carbon::create(2017, 6, 16, 14);

        Carbon::setTestNow($pretendedCurrentDate);

        $nextWeekDayDates = $service->nextWeekDayDates()->map(function ($day) {
            return $day->format('Y-m-d');
        });

        $this->assertEquals([
            '2017-06-19',
            '2017-06-20',
            '2017-06-21',
            '2017-06-22',
            '2017-06-23',
        ], $nextWeekDayDates->toArray());

        // Set to Friday 3 PM
        $pretendedCurrentDate = Carbon::create(2017, 6, 16, 15);

        Carbon::setTestNow($pretendedCurrentDate);

        $nextWeekDayDates = $service->nextWeekDayDates()->map(function ($day) {
            return $day->format('Y-m-d');
        });

        $this->assertEquals([
            '2017-06-26',
            '2017-06-27',
            '2017-06-28',
            '2017-06-29',
            '2017-06-30',
        ], $nextWeekDayDates->toArray());
    }
}
