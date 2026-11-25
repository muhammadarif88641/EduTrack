<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => 'nullable|string|max:255',
            'role' => 'nullable|in:student,lecturer',
            'status' => 'nullable|in:pending,approved,rejected',
        ]);

        $search = trim($filters['search'] ?? '');
        $role = $filters['role'] ?? '';
        $status = $filters['status'] ?? '';

        $query = User::whereIn('role', ['student', 'lecturer']);

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');

            });
        }

        if ($role !== '') {
            $query->where('role', $role);
        }

        if ($status !== '') {
            $query->where('account_status', $status);
        }

        $users = $query->latest()
            ->paginate(15)
            ->withQueryString();

        $totalStudents = User::where('role', 'student')
            ->count();

        $totalLecturers = User::where('role', 'lecturer')
            ->count();

        $totalPending = User::whereIn(
                'role',
                ['student', 'lecturer']
            )
            ->where('account_status', 'pending')
            ->count();

        $totalApproved = User::whereIn(
                'role',
                ['student', 'lecturer']
            )
            ->where('account_status', 'approved')
            ->count();

        return view('admin.users.index', compact(
            'users',
            'search',
            'role',
            'status',
            'totalStudents',
            'totalLecturers',
            'totalPending',
            'totalApproved'
        ));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:72|confirmed',
            'role' => 'required|in:student,lecturer',
        ]);

        $user = new User();

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = $validated['role'];
        $user->account_status = 'approved';

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User account created successfully.');
    }

    public function edit(User $user): View
    {
        abort_unless(
            in_array($user->role, ['student', 'lecturer'], true),
            403
        );

        return view('admin.users.edit', compact('user'));
    }

    public function update(
        Request $request,
        User $user
    ): RedirectResponse
    {
        abort_unless(
            in_array($user->role, ['student', 'lecturer'], true),
            403
        );

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'account_status' => 'required|in:pending,approved,rejected',

            'password' => 'nullable|string|min:8|max:72|confirmed',
        ]);

        $user->name = $validated['name'];

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        $user->email = $validated['email'];
        $user->account_status = $validated['account_status'];

        if (!empty($validated['password'])) {

            $user->password = Hash::make($validated['password']);
            $user->remember_token = null;

        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User information updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        return DB::transaction(function () use ($user) {

            $target = User::where('id', $user->id)
                ->lockForUpdate()
                ->firstOrFail();

            abort_unless(
                in_array($target->role, ['student', 'lecturer'], true),
                403
            );

            $hasCourses = DB::table('courses')
                ->where('lecturer_id', $target->id)
                ->exists();

            $hasEnrollments = DB::table('enrollments')
                ->where('student_id', $target->id)
                ->exists();

            $hasSubmissions = DB::table('submissions')
                ->where('student_id', $target->id)
                ->exists();

            if ($hasCourses || $hasEnrollments || $hasSubmissions) {

                return redirect()
                    ->route('admin.users.index')
                    ->with(
                        'error',
                        'This account cannot be deleted because it contains academic records. Change its account status instead.'
                    );

            }

            $target->delete();

            return redirect()
                ->route('admin.users.index')
                ->with('success', 'User account deleted successfully.');

        });
    }
}