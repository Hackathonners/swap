<?php

use Carbon\Carbon;
use App\Judite\Models\User;
use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use Illuminate\Database\Seeder;
use App\Judite\Models\Enrollment;
use App\Judite\Models\Invitation;

class GroupsFeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::transaction(function () {
            $this->enableGroupCreationPeriod();

            $courses = $this->getCourses();
            $students = $this->createUsersAndEnrollments($courses);

            $this->studentsJoinGroups($students, $courses);

            $this->createInvitations($students);
        });
    }

    private function enableGroupCreationPeriod()
    {
        $settings = app('settings');
        $settings->groups_creation_start_at = Carbon::yesterday()->subDays(5);
        $settings->groups_creation_end_at = Carbon::tomorrow()->addDays(5);
        $settings->save();
    }

    private function getCourses()
    {
        $courses = Course::take(5)->get();

        $courses->each(function ($course, $key) {
            $course->group_min = $key;
            $course->group_max = $key;

            $course->save();
        });

        return $courses;
    }

    private function createUsersAndEnrollments($courses)
    {
        $names = collect([
            'João Vilaça',
            'Diogo Couto',
            'Hugo Gonçalves',
            'André Brandão',
            'Miguel Costa',
            'Daniel Tavares',
            'José Luís Silva',
        ]);

        $numbers = collect([
            'a82339',
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

        $students = [];

        $names->each(function ($name, $key) use ($numbers, $emails, $courses, &$students) {
            $user = factory(User::class)->create([
                'name' => $name,
                'email' => $emails[$key],
                'password' => bcrypt('123456'),
                'verified' => true,
            ]);

            $student = factory(Student::class)->create([
                'user_id' => $user->id,
                'student_number' => $numbers[$key],
            ]);

            array_push($students, $student);

            $courses->each(function ($course) use ($student) {
                factory(Enrollment::class)->create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                ]);
            });
        });

        return $students;
    }

    private function studentsJoinGroups($students, $courses)
    {
        $courses->each(function ($course) use ($students) {
            $group = factory(Group::class)->create([
                'course_id' => $course->id,
            ]);

            if ($course->group_max != 4) {
                $students[0]->join($group);
            }
        });

        $group0 = Group::find(4);
        $students[1]->join($group0);

        $group1 = Group::find(5);
        $students[2]->join($group1);
        $students[3]->join($group1);

        $group2 = factory(Group::class)->create([
            'course_id' => $group1->course_id,
        ]);
        $students[4]->join($group2);
    }

    private function createInvitations($students)
    {
        $group5 = Group::find(5);
        $invitation = factory(Invitation::class)->create([
            'student_number' => $students[0]->student_number,
            'course_id' => $group5->course_id,
            'group_id' => $group5->id,
        ]);

        $group6 = Group::find(6);
        $invitation = factory(Invitation::class)->create([
            'student_number' => $students[0]->student_number,
            'course_id' => $group6->course_id,
            'group_id' => $group6->id,
        ]);
    }
}
