<?php

namespace App\Console\Commands;

use App\ApiAuthTokens;
use App\Classes;
use App\Lessons;
use App\Schools;
use App\StudentClassAssignments;
use Illuminate\Console\Command;
use Wonde\Client;

class WondeImportClasses extends Command
{
    protected $signature = 'wondeimportclasses:run';

    protected $description = 'Command to test the functionality of the Wonde API';

    public function handle() {

        echo "Beginning Wonde Classes Import\n";

        $authToken = ApiAuthTokens::where('service_name', '=', 'wonde')->pluck('token')->first();

        $client = new Client($authToken);

        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);

        $classes = $school->classes->all(['students', 'lessons.period'], []);

        foreach ($classes as $class) {
            $classRecord = Classes::updateOrCreate(
                ['wonde_id' => $class->id],
                ['name' => $class->name]
            );

            if (!is_null($class->students)) {
                foreach ($class->students->data as $students) {
                    StudentClassAssignments::firstOrCreate(['student_id' => $students->id, 'class_id' => $class->id]);
                }    
            }

            if (!is_null($class->lessons)) {
                foreach ($class->lessons->data as $lesson) {
                    Lessons::firstOrCreate(
                        ['wonde_id' => $lesson->id],
                        ['room_id' => $lesson->room,
                        'start_time' => $lesson->period->data->start_time,
                        'end_time' => $lesson->period->data->end_time,
                        'period_day' => $lesson->period->data->day,]
                    );
                }    
            }
        }

        echo "Wonde Classes Import Complete\n";
    }
}