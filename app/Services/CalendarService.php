<?php

namespace App\Services;

use App\Models\User;
use App\Models\Lessons;
use App\Models\Teachers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CalendarService
{
    /**
     * Generate an array of lessons the logged in user is assigned to.
     * 
     * @param array $weekDays
     * @return array
     */
    public function generateCalendarData($weekDays)
    {
        // TODO:
        // Issue with understanding of teacher-class relationship; Teacher has record for more than 1 class occurring at the same time.
        // A lesson can have an employee id associated who is the main class teacher.
        // Process needs refactored to include teacher_id in lesson table to restrict calendar view to only Main Class Teacher lessons on schedule.

        $calendarData = [];
        // Create an array of 5 minute intervals between the configured calendar start and end times. 
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));

        // Retrieve the logged in user object.
        $user = User::find(Auth::user()->id);

        // Retrieve all lessons the logged in user is assigned to as a teacher.
        $lessons = Lessons::select('lessons.room_id', 'lessons.start_time', 'lessons.end_time', 'lessons.period_day', 'c.name')
            ->join('classes as c', 'c.id', '=', 'lessons.class_id')
            ->join('teacher_class_assignments as tca', 'tca.class_id', '=', 'c.wonde_id')
            ->join('teachers as t', 't.id', '=', 'tca.teacher_id')
            ->where('t.wonde_id', '=', $user->teacher_id)
            ->get();

        foreach ($timeRange as $time) {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            foreach ($weekDays as $index => $day) {
                // TODO:
                // TimeService start_time and end_time have been configured to the format H:i
                // Lessons stat_time and end_time are stored in the format H:i:s
                // Develop better solution than concatenating :00 for comparison between two time formats.

                // Find the first lesson the teacher is assigned to for the exact weekDay and start_time.
                $lesson = $lessons->where('period_day', '=', $day)->where('start_time', '=',  $time['start'] . ':00')->first();

                // If a lesson exists for the specific day and time, log it into the calendarData and record the lesson time as the displayed cells height.
                if ($lesson) {
                    $startTime = Carbon::createFromFormat('H:i:s',  $lesson['start_time']);
                    $endTime = Carbon::createFromFormat('H:i:s',  $lesson['end_time']);
                    $difference = $startTime->diffInMinutes($endTime);

                    array_push($calendarData[$timeText], [
                        'class_name'   => $lesson['name'],
                        'room_id'      => $lesson['room_id'],
                        'rowspan'      => $difference/5 ?? ''
                    ]);
                } else if (!$lessons->where('period_day', '=', $day)->where('start_time', '<', $time['start'] . ':00')->where('end_time', '>=', $time['end'] . ':00')->count()) {
                    // If lesson exists which is outside the scope of the calendar.
                    array_push($calendarData[$timeText], 1);
                } else {
                    array_push($calendarData[$timeText], 0);
                }
            }
        }

        return $calendarData;
    }
}