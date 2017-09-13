<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ImportEnrollmentsFileTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function an_admin_can_import_enrollments()
    {
        // Prepare
        // Create an admin,
        // a collection with 30 enrollments without shifts and a copy of this collection but with shifts
        // and file with the enrollments with shifts.
        $admin = factory(User::class)->states('admin')->create();
        $enrollments = factory(Enrollment::class, 30)->create(['shift_id' => null]);

        // Create a copy of the enrollments and add shifts.
        $enrollmentsWithShifts = $enrollments->map(function ($enrollment) {
            $enrollmentClone = clone $enrollment;
            $course = $enrollmentClone->course;
            $shift = factory(Shift::class)->create(['course_id' => $course->id]);
            $enrollmentClone->shift_id = $shift->id;

            return $enrollmentClone;
        });

        // Create a file.
        $file = $this->createEnrollmentsFile($enrollmentsWithShifts);
        $requestData = ['enrollments' => $file];

        // Execute
        $response = $this->actingAs($admin)
            ->post(route('enrollments.storeImport'), $requestData);

        // Delete file
        File::delete($file->path());

        // Assert
        $response->assertRedirect(route('enrollments.import'));
        $actualEnrollments = Enrollment::all();
        $this->assertEquals($enrollmentsWithShifts->pluck('id'), $actualEnrollments->pluck('id'));
        $actualEnrollments->each(function ($enrollment) {
            $this->assertFalse(is_null($enrollment->shift));
        });
    }

    /** @test */
    public function students_may_not_import_enrollments()
    {
        // Prepare
        $student = factory(Student::class)->create();
        $enrollments = factory(Enrollment::class, 30)->create(['shift_id' => null]);

        // Create a copy of the enrollments and add shifts.
        $enrollments->map(function ($enrollment) {
            $enrollmentClone = clone $enrollment;
            $course = $enrollmentClone->course;
            $shift = factory(Shift::class)->create(['course_id' => $course->id]);
            $enrollmentClone->shift_id = $shift->id;

            return $enrollmentClone;
        });

        // Create a file.
        $requestData = [];

        // Execute
        $response = $this->actingAs($student->user)
            ->post(route('enrollments.storeImport'), $requestData);

        // Assert
        $response->assertStatus(404);
    }

    /** @test */
    public function enrollments_are_not_imported_when_student_is_invalid()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $student = factory(Student::class)->create();
        $otherStudent = factory(Student::class)->create();
        $enrollment = factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'shift_id' => null,
        ]);

        $enrollmentWithShift = clone $enrollment;
        $shift = factory(Shift::class)->create(['course_id' => $enrollment->course->id]);
        $enrollmentWithShift->shift_id = $shift->id;

        // Create a file.
        $file = $this->createEnrollmentsFile($enrollmentWithShift);
        $requestData = ['enrollments' => $file];

        // Remove student from database
        $enrollment->student()->associate($otherStudent)->save();
        $student->delete();

        // Execute
        $response = $this->actingAs($admin)
            ->post(route('enrollments.storeImport'), $requestData);

        // Delete file
        File::delete($file->path());

        // Assert
        $response->assertRedirect(route('enrollments.import'));
        $this->assertEquals(1, Enrollment::count());
        $actualEnrollment = Enrollment::first();
        $this->assertEquals($enrollment->id, $actualEnrollment->id);
        $this->assertEquals(null, $actualEnrollment->shift_id);
    }

    /** @test */
    public function enrollments_are_not_imported_when_course_is_invalid()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $course = factory(Course::class)->create();
        $otherCourse = factory(Course::class)->create();
        $enrollment = factory(Enrollment::class)->create([
            'course_id' => $course->id,
            'shift_id' => null,
        ]);

        $enrollmentWithShift = clone $enrollment;
        $shift = factory(Shift::class)->create(['course_id' => $course->id]);
        $enrollmentWithShift->shift_id = $shift->id;

        // Create a file.
        $file = $this->createEnrollmentsFile($enrollmentWithShift);
        $requestData = [
            'enrollments' => $file,
        ];

        // Remove course from database
        $enrollment->course()->associate($otherCourse)->save();
        $shift->delete();
        $course->delete();

        // Execute
        $response = $this->actingAs($admin)
            ->post(route('enrollments.storeImport'), $requestData);

        // Delete file
        File::delete($file->path());

        // Assert
        $response->assertRedirect(route('enrollments.import'));
        $this->assertEquals(1, Enrollment::count());
        $actualEnrollment = Enrollment::first();
        $this->assertEquals($enrollment->id, $actualEnrollment->id);
        $this->assertEquals(null, $actualEnrollment->shift_id);
    }

    /** @test */
    public function enrollments_are_inserted_when_they_are_absent()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $student = factory(Student::class)->create();
        $shift = factory(Shift::class)->create();
        $enrollment = factory(Enrollment::class)->make([
            'course_id' => $shift->course->id,
            'student_id' => $student->id,
            'shift_id' => $shift->id,
        ]);

        // Create a file.
        $file = $this->createEnrollmentsFile($enrollment);
        $requestData = ['enrollments' => $file];

        // Execute
        $response = $this->actingAs($admin)
            ->post(route('enrollments.storeImport'), $requestData);

        // Delete file
        File::delete($file->path());

        // Assert
        $response->assertRedirect(route('enrollments.import'));
        $this->assertEquals(1, Enrollment::count());
        tap(Enrollment::first(), function ($newEnrollment) use ($enrollment) {
            $this->assertEquals($enrollment->student->id, $newEnrollment->student->id);
            $this->assertEquals($enrollment->course->id, $newEnrollment->course->id);
            $this->assertEquals($enrollment->shift->id, $newEnrollment->shift->id);
        });
    }

    /**
     * A method to create a csv file with the given enrollments.
     *
     * @param \Illuminate\Database\Eloquent\Collection|\App\Judite\Models\Enrollment $data
     *
     * @return \Illuminate\Http\UploadedFile
     */
    private function createEnrollmentsFile($data)
    {
        $data = $data instanceof Collection ? $data : collect([$data]);

        $result = Excel::create('enrollments', function ($excel) use ($data) {
            $excel->sheet('Enrollments', function ($sheet) use ($data) {
                $rows = collect();
                $rows->push(['Course ID', 'Student ID', 'Shift']);
                $data->each(function ($enrollment) use ($rows) {
                    $rows->push([
                        $enrollment->course->code,
                        $enrollment->student->student_number,
                        $enrollment->shift->tag,
                    ]);
                });
                $sheet->rows($rows);
            });
        })->store('csv', false, true);

        return new UploadedFile($result['full'], $result['file'], '.csv', filesize($result['full']), null, true);
    }
}
