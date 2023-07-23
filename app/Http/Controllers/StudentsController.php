<?php

namespace App\Http\Controllers;

use App\Models\ApiAuthTokens;
use App\Models\Schools;
use App\Models\Teachers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Wonde\Client;

class StudentsController extends Controller
{
    /**
     * Display the logged in user's weekly student roster.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: 
        // Add ability for user to select a school on login or when trying to view page.
        // Currently school is hard coded to provided test school from Wonde.

        // Retrieve the logged in user object.
        $user = User::find(Auth::user()->id);
        // Retrieve the employee_id / employee Wonde ID for use in the Wonde API.
        $teacherId = Teachers::where('wonde_id', '=', $user->teacher_id)
            ->pluck('wonde_id')->first();

        // Array to contain the students from each day of the week.
        $weeklyStudents = [];
        $weekDays = config('weekdays');

        // Retrieve the auth token to connect to the Wonde API.
        $authToken = ApiAuthTokens::where('service_name', '=', 'wonde')->pluck('token')->first();

        // Create Wonde Client.
        $client = new Client($authToken);

        // Retrieve the Wonde School ID for the selected school.
        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);

        // Retrieve the employee data for the provided Wonde Employee ID.
        $employee = $school->employees->get($teacherId, ['employment_details', 'classes'], ['has_class' => true]);

        // Employees have multiple classes; loop through each class to obtain the students and the days they will be present.
        foreach ($employee->classes->data as $class) {
            // Retrieve the class using the class ids the employee teaches.
            // Includes class details, students within class, and lessons associated with class.
            $class = $school->classes->get($class->id, ['students', 'lessons.period']);

            $studentData = [];
            foreach ($class->students->data as $student) {
                $weeklyStudents[$student->id]['details'] = [];
                // Create a section in the array for each day (Mon-Fri) and default the student to not being present.
                foreach ($weekDays as $index => $day) {
                    $weeklyStudents[$student->id]['weekdays'][$day] = false;
                }

                // Store the students name details for display.
                $weeklyStudents[$student->id]['details']['forename'] = $student->forename;
                $weeklyStudents[$student->id]['details']['surname'] = $student->surname;
            }

            $daysData = [];
            foreach ($class->lessons->data as $lesson) {
                // Store each day the class will have a lesson into the daysData array.
                array_push($daysData, $lesson->period->data->day);
            }
            // Compact the array down to each unique day all students in this class will be present.
            $daysData = array_unique($daysData);

            // Mark each student in weeklyStudents as present for the weekdays found in daysData.
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