<?php

namespace App\Console\Commands;

use App\Models\Schools;
use App\Models\Students;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Wonde\Client;

class WondeImportStudents extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'wondeimportstudents:run';

    /**
     * The console command description.
     */
    protected $description = 'Command to import all students from Wonde API using a stored school ID.';

    /**
     * Execute the console command.
     * 
     * @return mixed
     */
    public function handle() {

        // TODO:
        // Implement try-catch cases for all client requests to gracefully handle exceptions.
        // GuzzleHttp exceptions implement BadResponseExcetion.

        echo "Beginning Wonde Students Import\n";

        // Get the auth token required for the API connection.
        $authToken = Config::get('services.wonde.key');

        // Create Wonde API client.
        $client = new Client($authToken);

        // Retrieve the School Wonde ID for the destination base uri.
        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);

        // Request all students from the connected school.
        $students = $school->students->all([], []);

        foreach ($students as $student) {
            // For each student retrieved, create a student record in the database.
            Students::updateOrCreate(
                ['wonde_id' => $student->id],
                ['upi' => $student->upi,
                'forename' => $student->forename,
                'surname' => $student->surname,
                ]
            );
        }

        echo "Wonde Students Import Complete\n";
    }
}