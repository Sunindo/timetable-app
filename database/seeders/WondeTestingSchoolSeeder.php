<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Schools;

class WondeTestingSchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * @return void.
     */
    public function run()
    {
        Model::unguard();

        Schools::firstOrCreate(['name' => 'Wonde Testing School', 'wonde_id' => 'A1930499544']);

        Model::reguard();
    }
}
