<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Notifications\InvitationEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;

class InvitationController extends Controller
{
    public function __construct()
    {
        // This single line connects all controller methods to their corresponding policy methods.
        // e.g., the `store()` method will automatically check the `create()` policy method.
        // the `edit()` method will automatically check the `update()` policy method.
        $this->authorizeResource(Invitation::class, 'invitation');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user()->fresh('sentInvitations.invitedBy');
        $invitations = $user->sentInvitations;
        return view('invitations.index', compact('invitations'));
    }

    public function details(Invitation $invitation)
    {
        // Authorize that the logged-in user sent this invitation
        $this->authorize('view', $invitation);

        $accountOwner = auth()->user();
        $managedUser = User::where('email', $invitation->email)->first();
        $canPromoteToAdmin = false;
        $isAlreadyAdmin = false; // Add this variable

        // If the invited user has completed registration, get their permission details
        if ($managedUser) {
            // Check if the user already has the role
            $isAlreadyAdmin = $managedUser->hasRole('Admin Co-Parent');

            $canPromoteToAdmin = $accountOwner->isPremium() &&
                (!$accountOwner->hasAdminCoParent() || $isAlreadyAdmin);
        }

        return view('invitations.details', [
            'invitation' => $invitation,
            'managedUser' => $managedUser,
            'canPromoteToAdmin' => $canPromoteToAdmin,
            'isAlreadyAdmin' => $isAlreadyAdmin, // Pass the new variable to the view
            'permissions' => $managedUser ? Permission::all()->groupBy(fn($item) => explode('-', $item->name)[1]) : [],
            'userPermissions' => $managedUser ? $managedUser->permissions->pluck('name')->toArray() : [],
        ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->canInvite()) {
            $user = auth()->user()->fresh('sentInvitations.invitedBy');
            $invitations = $user->sentInvitations;
            return redirect()->route('invitations.index', compact('invitations'))
                ->with('status', 'You have reached your invitation limit for the Basic plan. To invite another user, please remove an existing one or upgrade your plan.');
        }

        return view('invitations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->canInvite()) {
            return back()->withInput()->with('status', 'Invitation limit reached. You cannot send a new invitation on the Basic plan.');
        }

        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', Rule::unique('invitations')->where(function ($query) {
                return $query->where('status', 'pending');
            })],
            'role' => ['required', 'string', Rule::in(['co-parent', 'nanny', 'grandparent', 'guardian', 'other'])],
        ]);

        $invitation = Invitation::create([
            'email' => $request->email,
            'token' => Str::random(32),
            'invited_by' => auth()->id(),
            'role' => $request->role,
        ]);

        Notification::route('mail', $request->email)->notify(new InvitationEmail($invitation));

        return redirect()->route('invitations.index')->with('status', 'Invitation sent successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        $this->authorize('delete', $invitation);

        // Find the user who was invited, if they have already registered
        $invitedUser = User::where('email', $invitation->email)->first();

        // If a user account exists and was created via an invitation
        if ($invitedUser && $invitedUser->invited_by === auth()->id()) {
            // This is a critical step: delete the user's account.
            // Note: You might want to dispatch a job for this or handle related data cleanup.
            $invitedUser->delete();
        }

        // Now, delete the invitation record itself
        $invitation->delete();

        // Redirect back to the invitations index with a success message
        return redirect()->route('invitations.index')->with('status', 'The user/invitation has been successfully removed.');
    }

    public function showAcceptForm(string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('status', 'This invitation has already been ' . $invitation->status . '.');
        }

        return view('invitations.show', compact('invitation'));
    }

    public function acceptInvitation(Request $request, Invitation $invitation)
    {
        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'This invitation is not pending or has been revoked.');
        }

        $invitation->update(['status' => 'accepted']);

        if (auth()->guest()) {
            return redirect()->route('register', ['token' => $invitation->token])->with('status', 'Invitation accepted! Please complete your registration.');
        } else {
            return redirect()->route('dashboard')->with('status', 'Invitation accepted! You can now link this account to your existing profile.');
        }
    }

    public function rejectInvitation(Request $request, Invitation $invitation)
    {
        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')->with('error', 'This invitation is not pending or has been revoked.');
        }

        $invitation->update(['status' => 'rejected']);

        return redirect()->route('dashboard')->with('status', 'Invitation rejected.');
    }

    public function accept(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'name' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $invitation = Invitation::where('token', $request->token)->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'role' => $invitation->role,
            'invited_by' => $invitation->invited_by,
        ]);

        $user->assignRole('Invited User');

        $invitation->update(['registered_at' => now()]);

        event(new Registered($user));

        auth()->login($user);

        return redirect()->route('dashboard')->with('status', 'Invitation accepted! Welcome to Parent Planner.');
    }

    public function resend(Invitation $invitation)
    {
        if ($invitation->status !== 'pending') {
            return redirect()->route('invitations.index')->with('status', 'Invitation has already been ' . $invitation->status . '.');
        }

        $invitation->update(['token' => Str::random(32)]);

        Notification::route('mail', $invitation->email)->notify(new InvitationEmail($invitation));

        return redirect()->route('invitations.index')->with('status', 'Invitation resent successfully!');
    }
}
