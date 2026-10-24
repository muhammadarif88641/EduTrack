<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Step 1: validate informatioon for student
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            'unique:users',
        ],
        'password' => [
            'required',
            'confirmed',
            Rules\Password::defaults(),
        ],
    ]);

    // Step 2: create acoount student
    $user = new User();

    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);

    // Set atau pilih acount role dan status dia
    $user->role = 'student';
    $user->account_status = 'pending';

    $user->save();

    // Step 3:ke login tanpa perlu loging lagi
    return redirect()->route('login')->with(
        'status',
        'Registration successful! Please wait for Admin approval before logging in.'
    );
    }
}
