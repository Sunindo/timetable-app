<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use App\Models\Students;
use App\Models\Teachers;
use Illuminate\Http\Request;

class ClassesController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $teachers = Teachers::select('id', 'title', 'forename', 'surname')->get()->toArray();

        return view('classes.index', compact('teachers'));
    }

    public function getClasses(Request $request)
    {
        $data = [];

        $classes = Classes::select('classes.wonde_id', 'classes.name', 'l.start_time', 'l.end_time', 'l.period_day', 'l.day_value')
            ->join('teacher_class_assignments AS tca', 'tca.class_id', '=', 'classes.wonde_id')
            ->join('lessons as l', 'l.class_id', '=', 'classes.id')
            ->where('tca.teacher_id', '=', $request['teacher_id'])
            ->orderBy('l.day_value')
            ->orderBy('l.start_time')
            ->get()->toArray();

        foreach ($classes as $class_id => $class) {
            $data[$class_id]['class_name'] = $class['name'];
            // Probably not worth making a Carbon object to remove the unnecessary seconds.
            $data[$class_id]['start_time'] = substr($class['start_time'], 0, -3);
            $data[$class_id]['end_time'] = substr($class['end_time'], 0, -3);
            $data[$class_id]['period_day'] = $class['period_day'];
            
            $students = Students::select('forename', 'surname')
                ->join('student_class_assignments AS sca', 'sca.student_id', '=', 'students.wonde_id')
                ->where('sca.class_id', '=', $class['wonde_id'])
                ->get()->toArray();

            $data[$class_id]['students'] = [];
            foreach ($students as $student_id => $student) {
                $data[$class_id]['students'][$student_id] = ['forename' => $student['forename'], 'surname' => $student['surname']];
            }
        }

        $teachers = Teachers::select('id', 'title', 'forename', 'surname')->get()->toArray();

        // TODO:
        // FIX DATATABLES STYLINGS ON PAGE
        // MAKE SELECT DEFAULT TO SELECTED TEACHER
        // PRINT NAME OF TEACHER BEING SHOWN CLASSES

        return view('classes.index', compact('teachers', 'data'));
    }
}