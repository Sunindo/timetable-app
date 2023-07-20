<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;

class CalendarController extends Controller
{

    public function index(CalendarService $calendarService)
    {
        $weekDays = config('weekdays');

        $calendarData = $calendarService->generateCalendarData($weekDays);

        return view('calendar.index', compact('weekDays', 'calendarData'));
    }
}