<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DemoAcademicSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment('local')) {
            return;
        }

        $this->call(DemoStudentSeeder::class);

        DB::table('users')->insertOrIgnore([
            'name' => 'Demo Lecturer',
            'email' => 'lecturer@edutrack.example.test',
            'password' => Hash::make('Lecturer@12345'),
            'role' => 'lecturer',
            'account_status' => 'approved',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $lecturer = DB::table('users')
            ->where('email', 'lecturer@edutrack.example.test')
            ->where('role', 'lecturer')
            ->where('name', 'Demo Lecturer')
            ->first();

        if (!$lecturer) {
            return;
        }

        $students = DB::table('users')
            ->where('role', 'student')
            ->where('account_status', 'approved')
            ->where('email', 'like', 'student%@edutrack.example.test')
            ->where('name', 'like', 'Demo Student %')
            ->orderBy('email')
            ->get();

        $demoCourses = [
            [
                'course_code' => 'DEMO-DB01',
                'course_name' => 'Database Systems',
            ],
            [
                'course_code' => 'DEMO-WD01',
                'course_name' => 'Web Development',
            ],
        ];

        foreach ($demoCourses as $courseIndex => $demoCourse) {

            DB::table('courses')->insertOrIgnore([
                'lecturer_id' => $lecturer->id,
                'course_code' => $demoCourse['course_code'],
                'course_name' => $demoCourse['course_name'],
                'description' => 'Demonstration course for EduTrack testing.',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $course = DB::table('courses')
                ->where('course_code', $demoCourse['course_code'])
                ->where('lecturer_id', $lecturer->id)
                ->where('status', 'approved')
                ->first();

            if (!$course) {
                continue;
            }

            $assignments = [];

            for ($week = 1; $week <= 3; $week++) {

                $title = 'Demo Assignment ' . $week;

                $assignment = DB::table('assignments')
                    ->where('course_id', $course->id)
                    ->where('title', $title)
                    ->first();

                if (!$assignment) {

                    $dueDate = match ($week) {
                        1 => now()->subDays(14),
                        2 => now()->subDays(3),
                        3 => now()->addDays(7),
                    };

                    $id = DB::table('assignments')->insertGetId([
                        'course_id' => $course->id,
                        'title' => $title,
                        'instructions' => 'Complete the weekly learning exercises.',
                        'week_number' => $week,
                        'max_marks' => 100,
                        'due_date' => $dueDate,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $assignment = DB::table('assignments')
                        ->where('id', $id)
                        ->first();
                }

                $assignments[] = $assignment;
            }

            foreach ($students as $index => $student) {

                $status = match ($index % 10) {
                    8 => 'pending',
                    9 => 'rejected',
                    default => 'approved',
                };

                DB::table('enrollments')->insertOrIgnore([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $enrollment = DB::table('enrollments')
                    ->where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->first();

                if (!$enrollment || $enrollment->status !== 'approved') {
                    continue;
                }

                foreach ($assignments as $taskIndex => $assignment) {

                    $group = ($index + $courseIndex) % 8;

                    if ($group >= 5 && $taskIndex >= 1) {
                        continue;
                    }

                    if ($group >= 3 && $taskIndex === 2) {
                        continue;
                    }

                    $exists = DB::table('submissions')
                        ->where('assignment_id', $assignment->id)
                        ->where('student_id', $student->id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $graded = $taskIndex === 0
                        || ($taskIndex === 1 && $group < 3);

                    $marks = match (true) {
                        $group < 3 => 85 + ($group * 5),
                        $group < 5 => 60 + ($group * 3),
                        default => 30 + ($group * 2),
                    };

                    $path = 'submissions/demo-'
                        . $assignment->id . '-'
                        . $student->id . '.pdf';

                    if (!Storage::disk('local')->put(
                        $path,
                        $this->samplePdf()
                    )) {
                        continue;
                    }

                    $submittedAt = $assignment->due_date
                        ? Carbon::parse($assignment->due_date)->subDay()
                        : now();

                    if ($submittedAt->isFuture()) {
                        $submittedAt = now();
                    }

                    DB::table('submissions')->insertOrIgnore([
                        'assignment_id' => $assignment->id,
                        'student_id' => $student->id,
                        'file_path' => $path,
                        'original_filename' => 'Demo_Assignment.pdf',
                        'submitted_at' => $submittedAt,
                        'marks_obtained' => $graded ? $marks : null,
                        'feedback' => $graded
                            ? ($marks >= 60 ? 'Good work.' : 'Needs improvement.')
                            : null,
                        'status' => $graded ? 'graded' : 'submitted',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    private function samplePdf(): string
    {
        $content = "BT /F1 18 Tf 50 750 Td (EduTrack Demo Assignment) Tj ET";

        $objects = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 4 0 R >> >> /Contents 5 0 R >>',
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
            "<< /Length " . strlen($content) . " >>\nstream\n"
                . $content . "\nendstream",
        ];

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);

            $pdf .= ($index + 1)
                . " 0 obj\n"
                . $object
                . "\nendobj\n";
        }

        $xref = strlen($pdf);

        $pdf .= "xref\n0 6\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= 5; $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\n";
        $pdf .= "startxref\n$xref\n%%EOF";

        return $pdf;
    }
}