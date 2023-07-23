<?php

namespace App\Http\Controllers;

use App\Services\CalendarService;

class CalendarController extends Controller
{
    /**
     * Display the logged in user's Weekly Lesson Calendar.
     * 
     * @param CalendarService $calendarService
     * @return \Illuminate\Http\Response
     */
    public function index(CalendarService $calendarService)
    {
        $weekDays = config('weekdays');

        $calendarData = $calendarService->generateCalendarData($weekDays);

        return view('calendar.index', compact('weekDays', 'calendarData'));
    }
}