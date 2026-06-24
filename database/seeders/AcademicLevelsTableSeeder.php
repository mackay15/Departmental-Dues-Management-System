<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicLevel;

class AcademicLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            ['name' => 'Level 100', 'numeric_value' => 100],
            ['name' => 'Level 200', 'numeric_value' => 200],
            ['name' => 'Level 300', 'numeric_value' => 300],
            ['name' => 'Level 400', 'numeric_value' => 400],
            ['name' => 'Graduated', 'numeric_value' => 500],
        ];

        foreach ($levels as $lvl) {
            AcademicLevel::updateOrCreate(
                ['numeric_value' => $lvl['numeric_value']],
                ['name' => $lvl['name']]
            );
        }
    }
}
