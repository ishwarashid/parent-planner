<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Invitation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $invitation = null;
        $email = $request->email; // Default email from query string if any

        if ($request->has('token')) {
            $invitation = Invitation::where('token', $request->token)->first();
            if ($invitation) {
                $email = $invitation->email;
            }
        }

        return view('auth.register', compact('email', 'invitation'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $invited_by = null;
        $role = Role::PARENT; // Default role
        $invitation = null;

        if ($request->has('token')) {
            $invitation = Invitation::where('token', $request->token)->first();
            if ($invitation && $invitation->email === $request->email && $invitation->status === 'accepted') {
                $invited_by = $invitation->invited_by;
                $role = $invitation->role;
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'invited_by' => $invited_by,
            'role' => $role,
        ]);

        if ($invitation) {
            $invitation->update([
                'registered_at' => now(),
                'status' => 'registered',
            ]);
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
