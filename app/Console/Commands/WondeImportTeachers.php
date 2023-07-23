<?php

namespace App\Console\Commands;

use App\Models\ApiAuthTokens;
use App\Models\Schools;
use App\Models\TeacherClassAssignments;
use App\Models\Teachers;
use Illuminate\Console\Command;
use Wonde\Client;

class WondeImportTeachers extends Command
{
    protected $signature = 'wondeimportteachers:run';

    protected $description = 'Command to test the functionality of the Wonde API';

    public function handle() {

        echo "Beginning Wonde Teacher Import\n";

        $authToken = ApiAuthTokens::where('service_name', '=', 'wonde')->pluck('token')->first();

        $client = new Client($authToken);

        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);

        $employees = $school->employees->all(['employment_details', 'classes'], []);

        foreach ($employees as $employee) {
            if ($employee->employment_details->data->teaching_staff) {

                $teacher = Teachers::updateOrCreate(
                    ['wonde_id' => $employee->id],
                    ['upi' => $employee->upi,
                    'title' => $employee->title,
                    'forename' => $employee->forename,
                    'surname' => $employee->surname,]
                );

                foreach ($employee->classes->data as $class) {
                    TeacherClassAssignments::firstOrCreate(['teacher_id' => $teacher->id, 'class_id' => $class->id]);
                }
            }
        }

        echo "Wonde Teacher Import Complete\n";
    }
}