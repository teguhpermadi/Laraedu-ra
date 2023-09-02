<?php

namespace Database\Seeders;

use App\Models\StudentGrade;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentGrade::factory(10)->create()->each(function($query){
            StudentGrade::insertOrIgnore($query->toArray());
        });
    }
}
