<?php

namespace App\Console\Commands;

use App\Models\Schools;
use App\Models\TeacherClassAssignments;
use App\Models\Teachers;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Wonde\Client;

class WondeImportTeachers extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'wondeimportteachers:run';

    /**
     * The console command description.
     */
    protected $description = 'Command to import all teachers from Wonde API using a stored school ID.';

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

        echo "Beginning Wonde Teacher Import\n";

        // Get the auth token required for the API connection.
        $authToken = Config::get('services.wonde.key');

        // Create Wonde API client.
        $client = new Client($authToken);

        // Retrieve the School Wonde ID for the destination base uri.
        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);

        // Request all students from the connected school.
        $employees = $school->employees->all(['employment_details', 'classes'], []);

        foreach ($employees as $employee) {
            // For each employee retrieved, if they are marked as a member of the teaching staff, create a teacher record in the database.
            if ($employee->employment_details->data->teaching_staff) {
                $teacher = Teachers::updateOrCreate(
                    ['wonde_id' => $employee->id],
                    ['upi' => $employee->upi,
                    'title' => $employee->title,
                    'forename' => $employee->forename,
                    'surname' => $employee->surname,]
                );

                // For each class assoicated with the teacher, create a record in the database to display the relationship of a teacher assigned to a class
                foreach ($employee->classes->data as $class) {
                    TeacherClassAssignments::firstOrCreate(['teacher_id' => $teacher->id, 'class_id' => $class->id]);
                }
            }
        }

        echo "Wonde Teacher Import Complete\n";
    }
}