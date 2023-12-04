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
        // StudentCompetency::factory(200)->make()->each(function($query){
        //     StudentCompetency::insertOrIgnore($query->toArray());
        // });
        $studentCompetencies = StudentCompetency::all();
        foreach ($studentCompetencies as $studentCompetency) {

            $curriculum = $studentCompetency->student->studentGrade->teacherGrade->curriculum;
            
            if($curriculum == '2013'){
                StudentCompetency::find($studentCompetency->id)->update([
                    'score' => fake()->numberBetween(50,90),
                    'score_skill' => fake()->numberBetween(50,90),
                ]);
            } else {
                StudentCompetency::find($studentCompetency->id)->update([
                    'score' => fake()->numberBetween(50,90),
                ]);
            }
            
        }
    }
}
