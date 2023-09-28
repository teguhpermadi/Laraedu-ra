<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\User;
use App\Models\Userable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = fake()->randomElement(['male', 'female']);

        return [
            'name' => fake()->firstName($gender). ' '. fake()->lastName($gender),
            'gender' => $gender,
            'active' => fake()->randomElement([1,0]),
        ];
    }

    // create student with user role student
    public function userable()
    {
        return $this->afterCreating(function (Student $student) {
            $user = User::factory()->state([
                'name' => $student->name,
            ])->create();

            $user->assignRole('Student');
            
            $userable = Userable::create([
                'user_id' => $user->id,
                'userable_id' => $student->id,
                'userable_type' => student::class,
            ]);
        });        
    }
}
