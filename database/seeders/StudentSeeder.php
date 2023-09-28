<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // student with user
        Student::factory(25)->hasDataStudent()->userable()->create();
        // student without user
        Student::factory(25)->hasDataStudent()->create();
    }
}
