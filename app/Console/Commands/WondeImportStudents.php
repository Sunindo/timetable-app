<?php

namespace App\Console\Commands;

use App\ApiAuthTokens;
use App\Schools;
use App\Students;
use Illuminate\Console\Command;
use Wonde\Client;

class WondeImportStudents extends Command
{
    protected $signature = 'wondeimportstudents:run';

    protected $description = 'Command to test the functionality of the Wonde API';

    public function handle() {

        echo "Beginning Wonde Students Import\n";

        $authToken = ApiAuthTokens::where('service_name', '=', 'wonde')->pluck('token')->first();

        $client = new Client($authToken);

        $schoolId = Schools::where('name', '=', 'Wonde Testing School')->pluck('wonde_id')->first();
        $school = $client->school($schoolId);

        $students = $school->students->all([], []);

        foreach ($students as $student) {
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