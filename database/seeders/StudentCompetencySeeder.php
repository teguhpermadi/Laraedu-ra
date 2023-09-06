<?php

namespace Database\Seeders;

use App\Models\StudentCompetency;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentCompetencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentCompetency::factory(50)->make()->each(function($query){
            StudentCompetency::insertOrIgnore($query->toArray());
        });
    }
}
