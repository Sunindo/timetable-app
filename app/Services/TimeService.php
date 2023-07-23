<?php

namespace App\Services;

use Carbon\Carbon;

class TimeService
{
    /**
     * Create an array of intervals of 5 between the provided times.
     * 
     * @param time $from
     * @param time $to
     * @return array
     */
    public function generateTimeRange($from, $to)
    {
        $time = Carbon::parse($from);
        $timeRange = [];

        do {
            array_push($timeRange, [
                'start' => $time->format("H:i"),
                'end' => $time->addMinutes(5)->format("H:i")
            ]);
        } while ($time->format("H:i") !== $to);

        return $timeRange;
    }
}