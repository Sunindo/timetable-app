<?php

namespace App\Services;

use App\ApiAuthTokens;
use App\Schools;
use Wonde\Client;

class CalendarService
{
    public function generateCalendarData($weekDays)
    {
        $calendarData = [];
        $timeRange = (new TimeService)->generateTimeRange(config('app.calendar.start_time'), config('app.calendar.end_time'));
        // $lessons = Lesson::with('class', 'teacher')
        //     ->calendarByRoleOrClassId()
        //     ->get();

        $authToken = ApiAuthTokens::where('service_name', '=', 'wonde')->pluck('token')->first();

        $client = new Client($authToken);

        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);

        foreach ($school->lessons->all(['period', 'class', 'employee', 'room'], []) as $lesson) {
            var_dump($lesson);
            die();
        }

        foreach ($school->classes->all(['lessons'], ['has_students' => true, 'has_lessons' => true]) as $class) {
            var_dump($class);
            die();
        }

        foreach ($school->employees->all(['employment_details', 'classes'], ['has_class' => true]) as $employee) {
            // ->upi Unique Person Identifier - If a person is a contact and an employee they will have the same UPI but different ids
            var_dump($employee);
            die();

            // echo $employee->forename . ' ' . $employee->surname . ' - is a teacher? (' . $employee->employment_details->data->teaching_staff . ')';
            // echo "<br>";
        }



        // can get the employee with their classes
        // those class have lessons
        // can each classes lessons to fill the calendar
        // can also get each lessons students

        // step 1: get employee and their classes
        // step 2: get the classes' lessons
        // step 3: get the lessons students

        foreach ($timeRange as $time)
        {
            $timeText = $time['start'] . ' - ' . $time['end'];
            $calendarData[$timeText] = [];

            foreach ($weekDays as $index => $day)
            {
                // $lesson = $lessons->where('weekday', $index)->where('start_time', $time['start'])->first();

                // if ($lesson)
                // {
                //     array_push($calendarData[$timeText], [
                //         'class_name'   => $lesson->class->name,
                //         'teacher_name' => $lesson->teacher->name,
                //         'rowspan'      => $lesson->difference/30 ?? ''
                //     ]);
                // }
                // else if (!$lessons->where('weekday', $index)->where('start_time', '<', $time['start'])->where('end_time', '>=', $time['end'])->count())
                // {
                //     array_push($calendarData[$timeText], 1);
                // }
                // else
                // {
                    array_push($calendarData[$timeText], 1);
                // }
            }
        }

        return $calendarData;
    }
}