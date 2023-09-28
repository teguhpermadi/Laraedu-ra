<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // teacher with userable
        Teacher::factory(5)->hasDataTeacher()->userable()->create();
        // teacher without userable
        Teacher::factory(5)->hasDataTeacher()->create();
    }
}
