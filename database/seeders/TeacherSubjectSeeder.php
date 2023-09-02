<?php

namespace Database\Seeders;

use App\Models\TeacherSubject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TeacherSubject::factory(10)->create()->each(function($query){
            TeacherSubject::insertOrIgnore($query->toArray());
        });
    }
}
