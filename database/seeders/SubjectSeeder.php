<?php

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subjects = [
            ['name' => 'Pendidikan Agama Islam', 'code' => 'PAI'],
            ['name' => 'Pendidikan Pancasila dan Kewarganegaraan', 'code' => 'PPKn'],
            ['name' => 'Bahasa Indonesia', 'code' => 'BIN'],
            ['name' => 'Matematika', 'code' => 'MTK'],
            ['name' => 'Ilmu Pengetahuan Alam', 'code' => 'IPA'],
            ['name' => 'Ilmu Pengetahuan Sosial', 'code' => 'IPS'],
            ['name' => 'Seni Budaya dan Prakarya', 'code' => 'SBdP'],
        ];

        foreach ($subjects as $subject) {
            Subject::create($subject);
        }
    }
}
