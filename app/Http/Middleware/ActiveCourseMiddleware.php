<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ActiveCourseMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $courseId = null;

        if ($request->route('course') !== null) {

            $courseId = $this->getId($request->route('course'));

        } elseif ($request->route('material') !== null) {

            $courseId = DB::table('materials')
                ->where('id', $this->getId($request->route('material')))
                ->value('course_id');

        } elseif ($request->route('assignment') !== null) {

            $courseId = DB::table('assignments')
                ->where('id', $this->getId($request->route('assignment')))
                ->value('course_id');

        } elseif ($request->route('submission') !== null) {

            $courseId = DB::table('submissions')
                ->join(
                    'assignments',
                    'submissions.assignment_id',
                    '=',
                    'assignments.id'
                )
                ->where(
                    'submissions.id',
                    $this->getId($request->route('submission'))
                )
                ->value('assignments.course_id');

        } elseif ($request->route('enrollment') !== null) {

            $courseId = DB::table('enrollments')
                ->where(
                    'id',
                    $this->getId($request->route('enrollment'))
                )
                ->value('course_id');

        }

        abort_if($courseId === null, 404, 'Course not found.');

        return DB::transaction(function () use ($courseId, $request, $next) {

            $course = DB::table('courses')
                ->where('id', $courseId)
                ->lockForUpdate()
                ->first();

            abort_if(!$course, 404, 'Course not found.');

            abort_unless(
                $course->status === 'approved',
                403,
                'This course is not active. Modifications are not allowed.'
            );

            return $next($request);

        });
    }

    private function getId($value)
    {
        return $value instanceof Model
            ? $value->getKey()
            : $value;
    }
}