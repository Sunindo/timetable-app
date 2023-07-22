<?php

namespace App\Services;

use App\Lessons;
use Carbon\Carbon;

class CalendarService
{
    public function generateCalendarData($weekDays)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));

        $lessons = Lessons::select('lessons.room_id', 'lessons.start_time', 'lessons.end_time', 'lessons.period_day', 'c.name')
            ->join('classes as c', 'c.id', '=', 'lessons.class_id')
            ->join('teacher_class_assignments as tca', 'tca.class_id', '=', 'c.wonde_id')
            ->where('tca.teacher_id', '=', '68')
            ->get();

        foreach ($timeRange as $time) {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            foreach ($weekDays as $index => $day) {
                $lesson = $lessons->where('period_day', '=', $day)->where('start_time', '=',  $time['start'] . ':00')->first();
                $lessonOutsideScope = $lessons->where('period_day', '=', $day)->where('start_time', '<', $time['start'] . ':00')->where('end_time', '>=', $time['end'] . ':00')->count();
                // Concatenating :00 to end of string is terrible practice; replace with proper solution later

                if ($lesson) {
                    $startTime = Carbon::createFromFormat('H:i:s',  $lesson['start_time']);
                    $endTime = Carbon::createFromFormat('H:i:s',  $lesson['end_time']);
                    $difference = $startTime->diffInMinutes($endTime);

                    array_push($calendarData[$timeText], [
                        'class_name'   => $lesson['name'],
                        'room_id'      => $lesson['room_id'],
                        'rowspan'      => $difference/5 ?? ''
                    ]);
                } else if (!$lessonOutsideScope) {
                    array_push($calendarData[$timeText], 1);
                } else {
                    array_push($calendarData[$timeText], 0);
                }
            }
        }

        return $calendarData;
    }
}