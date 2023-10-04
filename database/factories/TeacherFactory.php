<?php

namespace Database\Factories;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Userable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Teacher>
 */
class TeacherFactory extends Factory
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
            'name' => fake()->name($gender),
            'gender' => $gender,
            'active' => fake()->randomElement([1,0]),
        ];
    }

    // create teacher with user role teacher
    public function userable()
    {
        return $this->afterCreating(function (Teacher $teacher) {
            $user = User::factory()->state([
                'name' => $teacher->name,
                'email' => Str::slug($teacher->name).'@teacher.com',
                'password' => Hash::make('password'),
            ])->create();
            $user->assignRole('Teacher');
            $userable = Userable::create([
                'user_id' => $user->id,
                'userable_id' => $teacher->id,
                'userable_type' => Teacher::class,
            ]);
        });        
    }
}
