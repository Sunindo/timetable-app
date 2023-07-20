<?php

namespace App\Http\Controllers;

use App\ApiAuthTokens;
use App\Schools;
use Illuminate\Http\Request;
use Wonde\Client;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Array to contain the students from each day of the week.
        $weeklyStudents = [];
        $weekDays = config('weekdays');

        // Retrieve the auth token to connect to the Wonde API.
        $authToken = ApiAuthTokens::where('service_name', '=', 'wonde')->pluck('token')->first();

        // Create Wonde Client.
        $client = new Client($authToken);

        // TODO: Add ability for user to select a school on login or when trying to view page.
        // Retrieve the Wonde API School ID for the selected school.
        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);

        // TODO: REPLACE THE HARD CODED STRING WITH THE EMPLOYEE ID FROM THE LOGIN PAGE
        // Retrieve the employee data for the provided Wonde Employee ID.
        $employee = $school->employees->get('A500460806', ['employment_details', 'classes'], ['has_class' => true]);

        // Employees have multiple classes; loop through each class to obtain the students and the days they will be taught.
        foreach ($employee->classes->data as $class) {
            // Retrieve the class using the class ids the employee teaches.
            // Includes class details, students within class, and lessons associated with class.
            $class = $school->classes->get($class->id, ['students', 'lessons.period']);

            $studentData = [];
            foreach ($class->students->data as $student) {
                $weeklyStudents[$student->id]['details'] = [];
                // Create a section in the array for each day (Mon-Fri).
                foreach ($weekDays as $index => $day) {
                    $weeklyStudents[$student->id]['weekdays'][$day] = false;
                }

                $weeklyStudents[$student->id]['details']['forename'] = $student->forename;
                $weeklyStudents[$student->id]['details']['surname'] = $student->surname;
            }

            $daysData = [];
            foreach ($class->lessons->data as $lesson) {
                array_push($daysData, $lesson->period->data->day);
            }
            $daysData = array_unique($daysData);

            foreach ($weeklyStudents as $id => $student) {
                foreach ($daysData as $day) {
                    $weeklyStudents[$id]['weekdays'][$day] = true;
                }
            }
        }
        
        // Return view with array students employee will see separated by day of the week.
        return view('students.index', compact('weekDays', 'weeklyStudents'));
    }
}