<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Programme;

class ProgrammesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programmes = [
            ['name' => 'Computer Science', 'code' => 'CS'],
            ['name' => 'Information and Communication Technology', 'code' => 'ICT'],
        ];

        foreach ($programmes as $prog) {
            Programme::updateOrCreate(
                ['code' => $prog['code']],
                ['name' => $prog['name']]
            );
        }
    }
}
?>
