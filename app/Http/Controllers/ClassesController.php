<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Students;
use App\Models\Teachers;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    /**
     * Display a listing of the Classes resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Retrieve all teachers from the database for selection.
        $teachers = Teachers::select('id', 'title', 'forename', 'surname')->get()->toArray();

        return view('classes.index', compact('teachers'));
    }

    /**
     * Returns the Classes assigned to the selected Teacher.
     * 
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function getClasses(Request $request)
    {
        // TODO:
        // Stored time values for lesson start_time and end_time were imported in the format H:i:s.
        // A preferable solution should be implemented to resolve the issue of the unecessary seconds.
        // Currently we are simply cutting off the last 3 characters of the string for display purposes.
        // Probably not worth extra resources to create a Carbon object for each timestring to remove the seconds.

        // Create array to store Classes data.
        $classData = [];

        // Select all classes that the selected teacher is assigned to.
        $classes = Classes::select('classes.wonde_id', 'classes.name', 'l.start_time', 'l.end_time', 'l.period_day', 'l.day_value')
            ->join('teacher_class_assignments AS tca', 'tca.class_id', '=', 'classes.wonde_id')
            ->join('lessons as l', 'l.class_id', '=', 'classes.id')
            ->where('tca.teacher_id', '=', $request['teacher_id'])
            ->orderBy('l.day_value')
            ->orderBy('l.start_time')
            ->get()->toArray();

        foreach ($classes as $class_id => $class) {
            // Store the Class name and period the Lesson occurs.
            $classData[$class_id]['class_name'] = $class['name'];
            $classData[$class_id]['start_time'] = substr($class['start_time'], 0, -3);
            $classData[$class_id]['end_time']   = substr($class['end_time'], 0, -3);
            $classData[$class_id]['period_day'] = $class['period_day'];
            
            // Retrieve all students who are assigned to the class.
            $students = Students::select('forename', 'surname')
                ->join('student_class_assignments AS sca', 'sca.student_id', '=', 'students.wonde_id')
                ->where('sca.class_id', '=', $class['wonde_id'])
                ->get()->toArray();

            // Store each student in the class for display.
            $classData[$class_id]['students'] = [];
            foreach ($students as $student_id => $student) {
                $classData[$class_id]['students'][$student_id] = ['forename' => $student['forename'], 'surname' => $student['surname']];
            }
        }

        // Retrieve all teachers for the select input and the details of the requested teacher.
        $teachers = Teachers::select('id', 'title', 'forename', 'surname')->get()->toArray();
        $selectedTeacher = Teachers::find($request['teacher_id']);

        return view('classes.index', compact('teachers', 'classData', 'selectedTeacher'));
    }
}