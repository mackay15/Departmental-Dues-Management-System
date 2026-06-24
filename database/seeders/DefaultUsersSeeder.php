<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DefaultUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Finance Officer
        $finance = User::firstOrCreate(
            ['email' => 'finance@compssa.edu.gh'],
            [
                'name' => 'Finance Officer',
                'password' => Hash::make('password'),
            ]
        );
        $finance->assignRole('Finance Officer');

        // HOD
        $hod = User::firstOrCreate(
            ['email' => 'hod@compssa.edu.gh'],
            [
                'name' => 'Head of Department',
                'password' => Hash::make('password'),
            ]
        );
        $hod->assignRole('HOD');

        // Student (Test)
        $student = User::firstOrCreate(
            ['email' => 'student@htu.edu.gh'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password'),
            ]
        );
        $student->assignRole('Student');
    }
}
