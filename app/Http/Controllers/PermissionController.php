<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function edit(User $user)
    {
        abort_if(auth()->id() !== $user->invited_by, 403, 'Unauthorized');

        $accountOwner = auth()->user();
        $canPromote = $accountOwner->isPremium() && (!$accountOwner->hasAdminCoParent() || $user->hasRole('Admin Co-Parent'));

        return view('users.permissions', [
            'managedUser' => $user,
            'permissions' => Permission::all()->groupBy(fn($item) => explode('-', $item->name)[1]),
            'userPermissions' => $user->permissions->pluck('name')->toArray(),
            'canPromoteToAdmin' => $canPromote, // Pass the promotion ability to the view
        ]);
    }

    public function update(Request $request, User $user)
    {
        $accountOwner = auth()->user();

        // Security check: Ensure the authenticated user is the one who invited the user being managed.
        abort_if($accountOwner->id !== $user->invited_by, 403, 'Unauthorized action.');

        $isPromoting = $request->boolean('promote_to_admin');

        if ($isPromoting) {
            logger('here1');
            abort_if(!$accountOwner->isPremium(), 403, 'This feature requires a premium subscription.');
            logger('here2');
            abort_if($accountOwner->hasAdminCoParent() && !$user->hasRole('Admin Co-Parent'), 403, 'You can only promote one user to an Admin role.');
            logger('here');


            $user->syncRoles(['Admin Co-Parent']);
        } else {
            // Demote or just update permissions
            if ($user->role == 'co-parent') {
                $user->syncRoles(['Co-Parent']);
            } else {
                $user->syncRoles(['Invited User']);
            }
            // $permissions = $request->input('permissions', []);
            // $user->syncPermissions($permissions);
        }

        // To redirect back to the details page, we need to find the original invitation
        $invitation = Invitation::where('email', $user->email)->firstOrFail();

        return redirect()->route('invitations.details', $invitation)->with('status', "Permissions for {$user->email} updated successfully.");
    }
}
