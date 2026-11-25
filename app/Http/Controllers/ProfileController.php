<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $userId = $request->user()->id;

        DB::transaction(function () use ($userId) {

            $user = \App\Models\User::where('id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($user->role, ['student', 'lecturer'], true)) {

                throw ValidationException::withMessages([
                    'password' => 'This account cannot be deleted.',
                ])->errorBag('userDeletion');

            }

            $hasCourses = DB::table('courses')
                ->where('lecturer_id', $user->id)
                ->exists();

            $hasEnrollments = DB::table('enrollments')
                ->where('student_id', $user->id)
                ->exists();

            $hasSubmissions = DB::table('submissions')
                ->where('student_id', $user->id)
                ->exists();

            if ($hasCourses || $hasEnrollments || $hasSubmissions) {

                throw ValidationException::withMessages([
                    'password' => 'Your account contains academic records and cannot be deleted. Please contact the administrator.',
                ])->errorBag('userDeletion');

            }

            $user->delete();

        });

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}