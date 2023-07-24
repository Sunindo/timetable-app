<?php

namespace App\Console\Commands;

use App\Models\Classes;
use App\Models\Lessons;
use App\Models\Schools;
use App\Models\StudentClassAssignments;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Wonde\Client;
use Wonde\Exceptions;

class WondeImportClasses extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'wondeimportclasses:run';

    /**
     * The console command description.
     */
    protected $description = 'Command to import all classes and lessons from Wonde API using a stored school ID.';

    /**
     * Execute the console command.
     * 
     * @return mixed
     */
    public function handle() {

        // TODO:
        // Implement try-catch cases for all client requests to gracefully handle exceptions.
        // GuzzleHttp exceptions implement BadResponseExcetion.
        // Refactor database columns to properly use foreign keys and relational modeling.

        echo "Beginning Wonde Classes Import\n";

        // Get the auth token required for the API connection.
        $authToken = Config::get('services.wonde.key');

        // Create Wonde API client.
        $client = new Client($authToken);

        // Retrieve the School Wonde ID for the destination base uri.
        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);
        
        // Request all classes including students and lessons associated with each from the connected school.
        $classes = $school->classes->all(['students', 'lessons.period'], []);

        $weekDays = config('weekdays');

        foreach ($classes as $class) {
            // For each class retrieved, create a class record in the database.
            $classRecord = Classes::updateOrCreate(
                ['wonde_id' => $class->id],
                ['name' => $class->name]
            );

            // If the class has students assigned, create a record in the database to display the relationship of a student assigned to a class.
            if (!is_null($class->students)) {
                foreach ($class->students->data as $students) {
                    StudentClassAssignments::firstOrCreate(['student_id' => $students->id, 'class_id' => $class->id]);
                }    
            }

            // If the class has lessons, create a record in the database for each lesson the class is expected to attend.
            if (!is_null($class->lessons)) {
                foreach ($class->lessons->data as $lesson) {
                    Lessons::firstOrCreate(
                        ['wonde_id'  => $lesson->id],
                        ['class_id'  => $classRecord->id,
                        'room_id'    => $lesson->room,
                        'start_time' => $lesson->period->data->start_time,
                        'end_time'   => $lesson->period->data->end_time,
                        'period_day' => $lesson->period->data->day,
                        'day_value'  => array_search($lesson->period->data->day, $weekDays)]
                    );
                }    
            }
        }

        echo "Wonde Classes Import Complete\n";
    }
}