<?php

use App\Judite\Models\User;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use Illuminate\Database\Seeder;
use App\Judite\Models\Enrollment;

class ImportFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::transaction(function () {
            // Create the courses
            $courses = Course::all();

            // Create the users and enrollments
            $names = collect([
                'Diogo Couto',
                'Hugo Gonçalves',
                'André Brandão',
                'Miguel Costa',
                'Daniel Tavares',
                'José Luís Silva',
            ]);
            $numbers = collect([
                'a71604',
                'a70363',
                'a71841',
                'a72362',
                'a71553',
                'a71220',
            ]);
            $emails = $numbers->map(function ($number) {
                return $number.'@alunos.uminho.pt';
            });
            $names->each(function ($name, $key) {
                $user = factory(User::class)->create([
                    'name' => $name,
                    'email' => $emails[$key],

                ]);
                $student = factory(Student::class)->create([
                    'user_id' => $user->id,
                    'student_number' => $emails[$key],
                ]);
                factory(Enrollment::class)->create([
                    'student_id' => $student->id,
                    'course_id' => $courses[$key]->id,
                ]);
            });
        });
    }
}
