<?php

namespace App\Exports;

use App\Judite\Models\Enrollment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class EnrollmentsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * The enrollments collection.
     *
     * @var \Illuminate\Support\Collection
     */
    private Collection $enrollments;

    /**
     * Construct a new EnrollmentsExport.
     *
     * @param \Illuminate\Support\Collection $enrollments
     */
    public function __construct(\Illuminate\Support\Collection $enrollments = null)
    {
        $this->enrollments = $enrollments;
    }

    /**
     * Headings of the exported enrollments.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Course ID',
            'Course Name',
            'Student ID',
            'Student Name',
            'Student Email',
            'Enrollment Date',
            'Shift',
        ];
    }

    /**
     * The enrollment to export.
     *
     * @param \App\Judite\Models\Enrollment $enrollment
     *
     * @return array
     */
    public function map($enrollment): array
    {
        return [
            $enrollment->course->code,
            $enrollment->course->name,
            $enrollment->student->student_number,
            $enrollment->student->user->name,
            $enrollment->student->user->email,
            $enrollment->student->created_at,
            $enrollment->present()->getShiftTag(''),
        ];
    }

    /**
     * The enrollments collection to export.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->enrollments) {
            return $this->enrollments;
        }

        return DB::transaction(fn () => Enrollment::with('course', 'student', 'shift')
            ->get()
            ->sortByDesc('courses.name')
        );
    }
}
